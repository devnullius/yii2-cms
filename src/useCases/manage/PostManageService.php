<?php
declare(strict_types=1);

namespace devnullius\cms\useCases\manage;

use devnullius\cms\entities\post\Post;
use devnullius\cms\entities\Tag;
use devnullius\cms\entities\Meta;
use devnullius\cms\forms\manage\post\PostForm;
use devnullius\cms\repositories\CategoryRepository;
use devnullius\cms\repositories\PostRepository;
use devnullius\cms\repositories\TagRepository;
use devnullius\queue\addon\wrappers\transaction\TransactionWrapper;
use Exception;
use Yii;

final class PostManageService
{
    private PostRepository $posts;
    private CategoryRepository $categories;
    private TagRepository $tags;
    private TransactionWrapper $transaction;

    public function __construct(
        PostRepository $posts,
        CategoryRepository $categories,
        TagRepository $tags,
        TransactionWrapper $transaction
    ) {
        $this->posts = $posts;
        $this->categories = $categories;
        $this->tags = $tags;
        $this->transaction = $transaction;
    }

    public function create(PostForm $form): Post
    {
        $category = $this->categories->get($form->categoryId);

        $post = Post::create(
            $category->id,
            $form->title,
            $form->description,
            $form->content,
            new Meta(
                $form->meta->title,
                $form->meta->description,
                $form->meta->keywords
            )
        );

        if ($form->photo) {
            $post->setPhoto($form->photo);
        }

        foreach ($form->tags->existing as $tagId) {
            $tag = $this->tags->get($tagId);
            $post->assignTag($tag->id);
        }

        try {
            $this->transaction->wrap(function () use ($post, $form) {
                foreach ($form->tags->newNames as $tagName) {
                    if (!$tag = $this->tags->findByName($tagName)) {
                        $tag = Tag::create($tagName, $tagName);
                        $this->tags->save($tag);
                    }
                    $post->assignTag($tag->id);
                }
                $this->posts->save($post);
            });
        } catch (Exception $e) {
            Yii::$app->errorHandler->logException($e);
        }

        return $post;
    }

    public function edit(int $id, PostForm $form): void
    {
        $post = $this->posts->get($id);
        $category = $this->categories->get($form->categoryId);

        $post->edit(
            $category->id,
            $form->title,
            $form->description,
            $form->content,
            new Meta(
                $form->meta->title,
                $form->meta->description,
                $form->meta->keywords
            )
        );

        if ($form->photo) {
            $post->setPhoto($form->photo);
        }

        try {
            $this->transaction->wrap(function () use ($post, $form) {

                $post->revokeTags();
                $this->posts->save($post);

                foreach ($form->tags->existing as $tagId) {
                    $tag = $this->tags->get($tagId);
                    $post->assignTag($tag->id);
                }
                foreach ($form->tags->newNames as $tagName) {
                    if (!$tag = $this->tags->findByName($tagName)) {
                        $tag = Tag::create($tagName, $tagName);
                        $this->tags->save($tag);
                    }
                    $post->assignTag($tag->id);
                }
                $this->posts->save($post);
            });
        } catch (Exception $e) {
            Yii::$app->errorHandler->logException($e);
        }
    }

    public function activate(int $id): void
    {
        $post = $this->posts->get($id);
        $post->activate();
        $this->posts->save($post);
    }

    public function draft(int $id): void
    {
        $post = $this->posts->get($id);
        $post->draft();
        $this->posts->save($post);
    }

    public function remove(int $id): void
    {
        $post = $this->posts->get($id);
        $this->posts->remove($post);
    }
}
