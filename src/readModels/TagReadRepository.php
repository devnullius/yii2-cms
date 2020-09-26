<?php
declare(strict_types=1);

namespace devnullius\cms\readModels;

use devnullius\cms\entities\Tag;

final class TagReadRepository
{
    public function findBySlug(string $slug): ?Tag
    {
        return Tag::findOne(['slug' => $slug]);
    }
}
