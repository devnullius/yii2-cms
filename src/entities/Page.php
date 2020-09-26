<?php
declare(strict_types=1);

namespace devnullius\cms\entities;

use devnullius\cms\entities\behaviors\MetaBehavior;
use paulzi\nestedsets\NestedSetsBehavior;
use yii\db\ActiveRecord;

/**
 * @property integer $id
 * @property string  $title
 * @property string  $slug
 * @property string  $content
 * @property integer $lft
 * @property integer $rgt
 * @property integer $depth
 * @property Meta    $meta
 *
 * @property Page    $parent
 * @property Page[]  $parents
 * @property Page[]  $children
 * @property Page    $prev
 * @property Page    $next
 * @mixin NestedSetsBehavior
 */
final class Page extends ActiveRecord
{
    public array $meta;

    public static function create(string $title, string $slug, string $content, Meta $meta): self
    {
        $category = new static();
        $category->title = $title;
        $category->slug = $slug;
        $category->title = $title;
        $category->content = $content;
        $category->meta = $meta;

        return $category;
    }

    public static function tableName(): string
    {
        return '{{%page}}';
    }

    public function edit(string $title, string $slug, string $content, Meta $meta): void
    {
        $this->title = $title;
        $this->slug = $slug;
        $this->content = $content;
        $this->meta = $meta;
    }

    public function getSeoTitle(): string
    {
        return $this->meta->title ?: $this->title;
    }

    public function behaviors(): array
    {
        return [
            MetaBehavior::class,
            NestedSetsBehavior::class,
        ];
    }

    public function transactions(): array
    {
        return [
            self::SCENARIO_DEFAULT => self::OP_ALL,
        ];
    }
}
