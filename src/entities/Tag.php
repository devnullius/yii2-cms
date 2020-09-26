<?php
declare(strict_types=1);

namespace devnullius\cms\entities;

use yii\db\ActiveRecord;

/**
 * @property integer $id
 * @property string  $name
 * @property string  $slug
 */
final class Tag extends ActiveRecord
{
    public static function create(string $name, string $slug): self
    {
        $tag = new static();
        $tag->name = $name;
        $tag->slug = $slug;

        return $tag;
    }

    public static function tableName(): string
    {
        return '{{%cms_tag}}';
    }

    public function edit(string $name, string $slug): void
    {
        $this->name = $name;
        $this->slug = $slug;
    }
}
