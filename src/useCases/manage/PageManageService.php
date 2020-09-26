<?php
declare(strict_types=1);

namespace devnullius\cms\useCases\manage;

use devnullius\cms\entities\Meta;
use devnullius\cms\entities\Page;
use devnullius\cms\forms\manage\PageForm;
use devnullius\cms\repositories\PageRepository;
use DomainException;

final class PageManageService
{
    private PageRepository $pages;

    public function __construct(PageRepository $pages)
    {
        $this->pages = $pages;
    }

    public function create(PageForm $form): Page
    {
        $parent = $this->pages->get($form->parentId);
        $page = Page::create(
            $form->title,
            $form->slug,
            $form->content,
            new Meta(
                $form->meta->title,
                $form->meta->description,
                $form->meta->keywords
            )
        );
        $page->appendTo($parent);
        $this->pages->save($page);

        return $page;
    }

    public function edit(int $id, PageForm $form): void
    {
        $page = $this->pages->get($id);
        $this->assertIsNotRoot($page);
        $page->edit(
            $form->title,
            $form->slug,
            $form->content,
            new Meta(
                $form->meta->title,
                $form->meta->description,
                $form->meta->keywords
            )
        );
        if ($form->parentId !== $page->parent->id) {
            $parent = $this->pages->get($form->parentId);
            $page->appendTo($parent);
        }
        $this->pages->save($page);
    }

    private function assertIsNotRoot(Page $page): void
    {
        if ($page->isRoot()) {
            throw new DomainException('Unable to manage the root page.');
        }
    }

    public function moveUp(int $id): void
    {
        $page = $this->pages->get($id);
        $this->assertIsNotRoot($page);
        if ($prev = $page->prev) {
            $page->insertBefore($prev);
        }
        $this->pages->save($page);
    }

    public function moveDown(int $id): void
    {
        $page = $this->pages->get($id);
        $this->assertIsNotRoot($page);
        if ($next = $page->next) {
            $page->insertAfter($next);
        }
        $this->pages->save($page);
    }

    public function remove(int $id): void
    {
        $page = $this->pages->get($id);
        $this->assertIsNotRoot($page);
        $this->pages->remove($page);
    }
}
