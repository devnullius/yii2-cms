<?php
declare(strict_types=1);

namespace devnullius\cms\readModels;

use devnullius\cms\entities\Page;

final class PageReadRepository
{
    public function getAll(): array
    {
        return Page::find()->andWhere(['>', 'depth', 0])->all();
    }

    public function find(int $id): ?Page
    {
        return Page::findOne($id);
    }

    public function findBySlug(string $slug): ?Page
    {
        return Page::find()->andWhere(['slug' => $slug])->andWhere(['>', 'depth', 0])->one();
    }
}
