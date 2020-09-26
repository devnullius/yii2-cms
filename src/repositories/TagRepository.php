<?php
declare(strict_types=1);

namespace devnullius\cms\repositories;

use devnullius\cms\entities\Tag;
use devnullius\cms\repositories\NotFoundException;
use RuntimeException;
use Throwable;
use yii\db\StaleObjectException;

final class TagRepository
{
    public function get(int $id): Tag
    {
        if (!$tag = Tag::findOne($id)) {
            throw new NotFoundException('Tag not found.');
        }

        return $tag;
    }

    public function findByName(string $name): ?Tag
    {
        return Tag::findOne(['name' => $name]);
    }

    public function save(Tag $tag): void
    {
        if (!$tag->save()) {
            throw new RuntimeException('Saving error.');
        }
    }

    public function remove(Tag $tag): void
    {
        try {
            if (!$tag->delete()) {
                throw new RuntimeException('Removing error.');
            }
        } catch (StaleObjectException $e) {
        } catch (Throwable $e) {
        }
    }
}
