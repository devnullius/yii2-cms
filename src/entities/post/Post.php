<?php
declare(strict_types=1);

namespace devnullius\cms\entities\post;

use devnullius\cms\entities\behaviors\MetaBehavior;
use devnullius\cms\entities\post\TagAssignment;
use devnullius\cms\entities\Category;
use devnullius\cms\entities\Meta;
use devnullius\cms\entities\post\queries\PostQuery;
use devnullius\cms\entities\Tag;
use DomainException;
use lhs\Yii2SaveRelationsBehavior\SaveRelationsBehavior;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use yii\web\UploadedFile;

/**
 * @see PostQuery
 * @property integer         $id
 * @property integer         $category_id
 * @property integer         $created_at
 * @property string          $title
 * @property string          $description
 * @property string          $content
 * @property string          $photo
 * @property integer         $status
 * @property integer         $comments_count
 *
 * @property Meta            $meta
 * @property Category        $category
 * @property TagAssignment[] $tagAssignments
 * @property Tag[]           $tags
 * @property Comment[]       $comments
 *
 * @mixin ImageUploadBehavior
 */
final class Post extends ActiveRecord
{
    public const STATUS_DRAFT = 10;
    public const STATUS_ACTIVE = 20;

    public array $meta;

    public static function create(int $categoryId, string $title, string $description, string $content, Meta $meta): self
    {
        $post = new static();
        $post->category_id = $categoryId;
        $post->title = $title;
        $post->description = $description;
        $post->content = $content;
        $post->meta = $meta;
        $post->status = self::STATUS_DRAFT;
        $post->created_at = time();
        $post->comments_count = 0;

        return $post;
    }

    public static function tableName(): string
    {
        return '{{%cms_post}}';
    }

    public static function find(): PostQuery
    {
        return new PostQuery(static::class);
    }

    public function setPhoto(UploadedFile $photo): void
    {
        $this->photo = $photo;
    }

    public function edit(int $categoryId, string $title, string $description, string $content, Meta $meta): void
    {
        $this->category_id = $categoryId;
        $this->title = $title;
        $this->description = $description;
        $this->content = $content;
        $this->meta = $meta;
    }

    public function activate(): void
    {
        if ($this->isActive()) {
            throw new DomainException('Post is already active.');
        }
        $this->status = self::STATUS_ACTIVE;
    }

    public function isActive(): bool
    {
        return $this->status === self::STATUS_ACTIVE;
    }

    public function draft(): void
    {
        if ($this->isDraft()) {
            throw new DomainException('Post is already draft.');
        }
        $this->status = self::STATUS_DRAFT;
    }

    // Tags

    public function isDraft(): bool
    {
        return $this->status === self::STATUS_DRAFT;
    }

    public function getSeoTitle(): string
    {
        return $this->meta->title ?: $this->title;
    }

    public function assignTag(int $id): void
    {
        $assignments = $this->tagAssignments;
        foreach ($assignments as $assignment) {
            if ($assignment->isForTag($id)) {
                return;
            }
        }
        $assignments[] = TagAssignment::create($id);
        $this->tagAssignments = $assignments;
    }

    // Comments

    public function revokeTag(int $id): void
    {
        $assignments = $this->tagAssignments;
        foreach ($assignments as $i => $assignment) {
            if ($assignment->isForTag($id)) {
                unset($assignments[$i]);
                $this->tagAssignments = $assignments;

                return;
            }
        }
        throw new DomainException('Assignment not found.');
    }

    public function revokeTags(): void
    {
        $this->tagAssignments = [];
    }

    public function addComment(int $userId, int $parentId, string $text): Comment
    {
        $parent = $parentId ? $this->getComment($parentId) : null;
        if ($parent && !$parent->isActive()) {
            throw new DomainException('Cannot add comment to inactive parent.');
        }
        $comments = $this->comments;
        $comments[] = $comment = Comment::create($userId, $parent ? $parent->id : null, $text);
        $this->updateComments($comments);

        return $comment;
    }

    public function getComment(int $id): Comment
    {
        foreach ($this->comments as $comment) {
            if ($comment->isIdEqualTo($id)) {
                return $comment;
            }
        }
        throw new DomainException('Comment not found.');
    }

    private function updateComments(array $comments): void
    {
        $this->comments = $comments;
        $this->comments_count = count(array_filter($comments, function (Comment $comment) {
            return $comment->isActive();
        }));
    }

    public function editComment(int $id, int $parentId, string $text): void
    {
        $parent = $parentId ? $this->getComment($parentId) : null;
        $comments = $this->comments;
        foreach ($comments as $comment) {
            if ($comment->isIdEqualTo($id)) {
                $comment->edit($parent ? $parent->id : null, $text);
                $this->updateComments($comments);

                return;
            }
        }
        throw new DomainException('Comment not found.');
    }

    public function activateComment(int $id): void
    {
        $comments = $this->comments;
        foreach ($comments as $comment) {
            if ($comment->isIdEqualTo($id)) {
                $comment->activate();
                $this->updateComments($comments);

                return;
            }
        }
        throw new DomainException('Comment not found.');
    }

    ##########################

    public function removeComment(int $id): void
    {
        $comments = $this->comments;
        foreach ($comments as $i => $comment) {
            if ($comment->isIdEqualTo($id)) {
                if ($this->hasChildren($comment->id)) {
                    $comment->draft();
                } else {
                    unset($comments[$i]);
                }
                $this->updateComments($comments);

                return;
            }
        }
        throw new DomainException('Comment not found.');
    }

    private function hasChildren(int $id): bool
    {
        foreach ($this->comments as $comment) {
            if ($comment->isChildOf($id)) {
                return true;
            }
        }

        return false;
    }

    public function getCategory(): ActiveQuery
    {
        return $this->hasOne(Category::class, ['id' => 'category_id']);
    }

    public function getTagAssignments(): ActiveQuery
    {
        return $this->hasMany(TagAssignment::class, ['post_id' => 'id']);
    }

    ##########################

    public function getTags(): ActiveQuery
    {
        return $this->hasMany(Tag::class, ['id' => 'tag_id'])->via('tagAssignments');
    }

    public function getComments(): ActiveQuery
    {
        return $this->hasMany(Comment::class, ['post_id' => 'id']);
    }

    public function behaviors(): array
    {
        return [
            MetaBehavior::class,
            [
                'class' => SaveRelationsBehavior::class,
                'relations' => ['tagAssignments', 'comments'],
            ],
            [
                'class' => ImageUploadBehavior::className(),
                'attribute' => 'photo',
                'createThumbsOnRequest' => true,
                'filePath' => '@staticRoot/origin/posts/[[id]].[[extension]]',
                'fileUrl' => '@static/origin/posts/[[id]].[[extension]]',
                'thumbPath' => '@staticRoot/cache/posts/[[profile]]_[[id]].[[extension]]',
                'thumbUrl' => '@static/cache/posts/[[profile]]_[[id]].[[extension]]',
                'thumbs' => [
                    'admin' => ['width' => 100, 'height' => 70],
                    'thumb' => ['width' => 640, 'height' => 480],
                    'blog_list' => ['width' => 1000, 'height' => 150],
                    'widget_list' => ['width' => 228, 'height' => 228],
                    //                    'origin' => ['processor' => [new WaterMarker(1024, 768, '@devnullius\cms/web/image/logo.png'), 'process']],
                ],
            ],
        ];
    }

    public function transactions(): array
    {
        return [
            self::SCENARIO_DEFAULT => self::OP_ALL,
        ];
    }
}
