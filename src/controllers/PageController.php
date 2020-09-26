<?php
declare(strict_types=1);

namespace devnullius\cms\controllers;

use devnullius\cms\readModels\PageReadRepository;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

final class PageController extends Controller
{
    private PageReadRepository $pages;

    public function __construct($id, $module, PageReadRepository $pages, $config = [])
    {
        parent::__construct($id, $module, $config);
        $this->pages = $pages;
    }

    /**
     * @param $id
     *
     * @return mixed
     * @throws NotFoundHttpException
     * @internal param string $slug
     */
    public function actionView(int $id): string
    {
        if (!$page = $this->pages->find($id)) {
            throw new NotFoundHttpException('The requested page does not exist.');
        }

        return $this->render('view', [
            'page' => $page,
        ]);
    }
}
