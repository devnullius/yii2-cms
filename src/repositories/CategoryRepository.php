<?php
declare(strict_types=1);

namespace devnullius\cms\repositories;

use RuntimeException;
use devnullius\cms\entities\Category;
use devnullius\cms\repositories\NotFoundException;
use Throwable;
use yii\db\StaleObjectException;

final class CategoryRepository
{
    public function get(int $id): Category
    {
        if (!$category = Category::findOne($id)) {
            throw new NotFoundException('Category not found.');
        }

        return $category;
    }

    public function save(Category $category): void
    {
        if (!$category->save()) {
            throw new RuntimeException('Saving error.');
        }
    }

    public function remove(Category $category): void
    {
        try {
            if (!$category->delete()) {
                throw new RuntimeException('Removing error.');
            }
        } catch (StaleObjectException $e) {
        } catch (Throwable $e) {
        }
    }
}
