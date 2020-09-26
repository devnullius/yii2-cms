<?php
declare(strict_types=1);

namespace devnullius\cms\useCases\manage;

use devnullius\cms\entities\Tag;
use devnullius\cms\forms\manage\TagForm;
use devnullius\cms\repositories\TagRepository;

final class TagManageService
{
    private TagRepository $tags;

    public function __construct(TagRepository $tags)
    {
        $this->tags = $tags;
    }

    public function create(TagForm $form): Tag
    {
        $tag = Tag::create(
            $form->name,
            $form->slug
        );
        $this->tags->save($tag);

        return $tag;
    }

    public function edit(int $id, TagForm $form): void
    {
        $tag = $this->tags->get($id);
        $tag->edit(
            $form->name,
            $form->slug
        );
        $this->tags->save($tag);
    }

    public function remove(int $id): void
    {
        $tag = $this->tags->get($id);
        $this->tags->remove($tag);
    }
}
