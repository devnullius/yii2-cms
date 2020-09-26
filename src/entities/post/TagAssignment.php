<?php
declare(strict_types=1);

namespace devnullius\cms\entities\post;

use yii\db\ActiveRecord;

/**
 * @property integer $post_id;
 * @property integer $tag_id ;
 */
final class TagAssignment extends ActiveRecord
{
    public static function create(int $tagId): self
    {
        $assignment = new static();
        $assignment->tag_id = $tagId;

        return $assignment;
    }

    public static function tableName(): string
    {
        return '{{%cms_tag_assignment}}';
    }

    public function isForTag(int $id): bool
    {
        return $this->tag_id === $id;
    }
}
