<?php
declare(strict_types=1);

namespace devnullius\cms\entities\post;

use devnullius\cms\entities\post\queries\PostQuery;
use devnullius\helper\helpers\FlagHelper;
use yii\db\ActiveRecord;

/**
 * @property int    $id
 * @property int    $created_at
 * @property int    $post_id
 * @property int    $user_id
 * @property int    $parent_id
 * @property string $text
 * @property bool   $active
 *
 * @property Post   $post
 */
final class Comment extends ActiveRecord
{
    public static function create(int $userId, int $parentId, string $text): self
    {
        $review = new static();
        $review->user_id = $userId;
        $review->parent_id = $parentId;
        $review->text = $text;
        $review->created_at = time();
        $review->active = FlagHelper::IS_ACTIVE;

        return $review;
    }

    public static function tableName(): string
    {
        return '{{%cms_comment}}';
    }

    public function edit(int $parentId, string $text): void
    {
        $this->parent_id = $parentId;
        $this->text = $text;
    }

    public function activate(): void
    {
        $this->active = FlagHelper::IS_ACTIVE;
    }

    public function draft(): void
    {
        $this->active = FlagHelper::IS_DRAFT;
    }

    public function isActive(): bool
    {
        return $this->active === FlagHelper::IS_ACTIVE;
    }

    public function isIdEqualTo(int $id): bool
    {
        return $this->id === $id;
    }

    public function isChildOf(int $id): bool
    {
        return $this->parent_id === $id;
    }

    public function getPost(): PostQuery
    {
        return $this->hasOne(Post::class, ['id' => 'post_id']);
    }
}
