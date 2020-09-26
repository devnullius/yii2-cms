<?php
declare(strict_types=1);

namespace devnullius\cms\entities;

use devnullius\cms\entities\behaviors\MetaBehavior;
use yii\db\ActiveRecord;

/**
 * @property integer $id
 * @property string  $name
 * @property string  $slug
 * @property string  $title
 * @property string  $description
 * @property int     $sort
 * @property Meta    $meta
 */
final class Category extends ActiveRecord
{
    public array $meta;

    public static function create(string $name, string $slug, string $title, string $description, int $sort, Meta $meta): self
    {
        $category = new static();
        $category->name = $name;
        $category->slug = $slug;
        $category->title = $title;
        $category->description = $description;
        $category->sort = $sort;
        $category->meta = $meta;

        return $category;
    }

    public static function tableName(): string
    {
        return '{{%cms_category}}';
    }

    public function edit(string $name, string $slug, string $title, string $description, int $sort, Meta $meta): void
    {
        $this->name = $name;
        $this->slug = $slug;
        $this->title = $title;
        $this->description = $description;
        $this->sort = $sort;
        $this->meta = $meta;
    }

    public function getSeoTitle(): string
    {
        return $this->meta->title ?: $this->getHeadingTile();
    }

    public function getHeadingTile(): string
    {
        return $this->title ?: $this->name;
    }

    public function behaviors(): array
    {
        return [
            MetaBehavior::class,
        ];
    }
}
