<?php
declare(strict_types=1);

namespace devnullius\cms\controllers\admin;

use devnullius\cms\entities\Page;
use devnullius\cms\forms\manage\PageForm;
use devnullius\cms\search\PageSearch;
use devnullius\cms\useCases\manage\PageManageService;
use DomainException;
use Yii;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\Response;

final class PageController extends Controller
{
    private PageManageService $service;

    public function __construct($id, $module, PageManageService $service, $config = [])
    {
        parent::__construct($id, $module, $config);
        $this->service = $service;
    }

    public function behaviors(): array
    {
        return [
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * @return string
     */
    public function actionIndex(): string
    {
        $searchModel = new PageSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * @param int $id
     *
     * @return string
     * @throws NotFoundHttpException
     */
    public function actionView(int $id): string
    {
        return $this->render('view', [
            'page' => $this->findModel($id),
        ]);
    }

    /**
     * @param int $id
     *
     * @return Page
     * @throws NotFoundHttpException
     */
    private function findModel(int $id): Page
    {
        if (($model = Page::findOne($id)) !== null) {
            return $model;
        }
        throw new NotFoundHttpException('The requested page does not exist.');
    }

    /**
     * @return string|Response
     */
    public function actionCreate()
    {
        $form = new PageForm();
        if ($form->load(Yii::$app->request->post()) && $form->validate()) {
            try {
                $page = $this->service->create($form);

                return $this->redirect(['view', 'id' => $page->id]);
            } catch (DomainException $e) {
                Yii::$app->errorHandler->logException($e);
                Yii::$app->session->setFlash('error', $e->getMessage());
            }
        }

        return $this->render('create', [
            'model' => $form,
        ]);
    }

    /**
     * @param int $id
     *
     * @return string|Response
     * @throws NotFoundHttpException
     */
    public function actionUpdate(int $id)
    {
        $page = $this->findModel($id);

        $form = new PageForm($page);
        if ($form->load(Yii::$app->request->post()) && $form->validate()) {
            try {
                $this->service->edit($page->id, $form);

                return $this->redirect(['view', 'id' => $page->id]);
            } catch (DomainException $e) {
                Yii::$app->errorHandler->logException($e);
                Yii::$app->session->setFlash('error', $e->getMessage());
            }
        }

        return $this->render('update', [
            'model' => $form,
            'page' => $page,
        ]);
    }

    /**
     * @param int $id
     *
     * @return Response
     */
    public function actionDelete(int $id): Response
    {
        try {
            $this->service->remove($id);
        } catch (DomainException $e) {
            Yii::$app->errorHandler->logException($e);
            Yii::$app->session->setFlash('error', $e->getMessage());
        }

        return $this->redirect(['index']);
    }

    /**
     * @param int $id
     *
     * @return Response
     */
    public function actionMoveUp(int $id): Response
    {
        $this->service->moveUp($id);

        return $this->redirect(['index']);
    }

    /**
     * @param int $id
     *
     * @return Response
     */
    public function actionMoveDown(int $id): Response
    {
        $this->service->moveDown($id);

        return $this->redirect(['index']);
    }
}
