<?php
declare(strict_types=1);

namespace devnullius\cms\repositories;

use RuntimeException;
use devnullius\cms\entities\post\Post;
use devnullius\cms\repositories\NotFoundException;
use Throwable;
use yii\db\StaleObjectException;

final class PostRepository
{
    public function get(int $id): Post
    {
        if (!$brand = Post::findOne($id)) {
            throw new NotFoundException('Post not found.');
        }

        return $brand;
    }

    public function existsByCategory(int $id): bool
    {
        return Post::find()->andWhere(['category_id' => $id])->exists();
    }

    public function save(Post $brand): void
    {
        if (!$brand->save()) {
            throw new RuntimeException('Saving error.');
        }
    }

    public function remove(Post $brand): void
    {
        try {
            if (!$brand->delete()) {
                throw new RuntimeException('Removing error.');
            }
        } catch (StaleObjectException $e) {
        } catch (Throwable $e) {
        }
    }
}
