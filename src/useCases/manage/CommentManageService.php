<?php
declare(strict_types=1);

namespace devnullius\cms\useCases\manage;

use devnullius\cms\forms\manage\post\CommentEditForm;
use devnullius\cms\repositories\PostRepository;

final class CommentManageService
{
    private PostRepository $posts;

    public function __construct(PostRepository $posts)
    {
        $this->posts = $posts;
    }

    public function edit(int $postId, int $id, CommentEditForm $form): void
    {
        $post = $this->posts->get($postId);
        $post->editComment($id, $form->parentId, $form->text);
        $this->posts->save($post);
    }

    public function activate(int $postId, int $id): void
    {
        $post = $this->posts->get($postId);
        $post->activateComment($id);
        $this->posts->save($post);
    }

    public function remove(int $postId, int $id): void
    {
        $post = $this->posts->get($postId);
        $post->removeComment($id);
        $this->posts->save($post);
    }
}
