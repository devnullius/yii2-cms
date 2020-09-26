<?php
declare(strict_types=1);

namespace devnullius\cms\useCases;

use devnullius\cms\entities\post\Comment;
use devnullius\cms\forms\CommentForm;
use devnullius\cms\repositories\PostRepository;
use devnullius\cms\repositories\UserRepository;

class CommentService
{
    private $posts;
    private $users;

    public function __construct(PostRepository $posts, UserRepository $users)
    {
        $this->posts = $posts;
        $this->users = $users;
    }

    public function create($postId, $userId, CommentForm $form): Comment
    {
        $post = $this->posts->get($postId);
        $user = $this->users->get($userId);

        $comment = $post->addComment($user->id, $form->parentId, $form->text);

        $this->posts->save($post);

        return $comment;
    }
}
