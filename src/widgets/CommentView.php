<?php
declare(strict_types=1);

namespace devnullius\cms\widgets;

use devnullius\cms\entities\post\Comment;

class CommentView
{
    public Comment $comment;
    /**
     * @var self[]
     */
    public array $children;

    public function __construct(Comment $comment, array $children)
    {
        $this->comment = $comment;
        $this->children = $children;
    }
}
