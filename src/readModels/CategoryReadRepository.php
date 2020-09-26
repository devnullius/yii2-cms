<?php
declare(strict_types=1);

namespace devnullius\cms\readModels;

use devnullius\cms\entities\Category;

final class CategoryReadRepository
{
    public function getAll(): array
    {
        return Category::find()->orderBy('sort')->all();
    }

    public function find(int $id): ?Category
    {
        return Category::findOne($id);
    }

    public function findBySlug(string $slug): ?Category
    {
        return Category::find()->andWhere(['slug' => $slug])->one();
    }
}
