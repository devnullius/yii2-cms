<?php
declare(strict_types=1);

namespace devnullius\cms\useCases\manage;

use devnullius\cms\entities\Category;
use devnullius\cms\entities\Meta;
use devnullius\cms\forms\manage\CategoryForm;
use devnullius\cms\repositories\CategoryRepository;
use devnullius\cms\repositories\PostRepository;
use DomainException;

final class CategoryManageService
{
    private CategoryRepository $categories;
    private PostRepository $posts;

    public function __construct(CategoryRepository $categories, PostRepository $posts)
    {
        $this->categories = $categories;
        $this->posts = $posts;
    }

    public function create(CategoryForm $form): Category
    {
        $category = Category::create(
            $form->name,
            $form->slug,
            $form->title,
            $form->description,
            $form->sort,
            new Meta(
                $form->meta->title,
                $form->meta->description,
                $form->meta->keywords
            )
        );
        $this->categories->save($category);

        return $category;
    }

    public function edit(int $id, CategoryForm $form): void
    {
        $category = $this->categories->get($id);
        $category->edit(
            $form->name,
            $form->slug,
            $form->title,
            $form->description,
            $form->sort,
            new Meta(
                $form->meta->title,
                $form->meta->description,
                $form->meta->keywords
            )
        );
        $this->categories->save($category);
    }

    public function remove(int $id): void
    {
        $category = $this->categories->get($id);
        if ($this->posts->existsByCategory($category->id)) {
            throw new DomainException('Unable to remove category with posts.');
        }
        $this->categories->remove($category);
    }
}
