<?php
declare(strict_types=1);

namespace devnullius\cms\widgets;

use devnullius\cms\readModels\PostReadRepository;
use yii\base\Widget;

class LastPostsWidget extends Widget
{
    public int $limit;

    private PostReadRepository $repository;

    public function __construct(PostReadRepository $repository, $config = [])
    {
        parent::__construct($config);
        $this->repository = $repository;
    }

    public function run(): string
    {
        return $this->render('last-posts', [
            'posts' => $this->repository->getLast($this->limit),
        ]);
    }
}
