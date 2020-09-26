<?php
declare(strict_types=1);

namespace devnullius\cms\controllers\admin;

use devnullius\cms\entities\post\Post;
use devnullius\cms\forms\manage\post\CommentEditForm;
use devnullius\cms\search\CommentSearch;
use devnullius\cms\useCases\manage\CommentManageService;
use DomainException;
use Yii;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\Response;

final class CommentController extends Controller
{
    private CommentManageService $service;

    public function __construct($id, $module, CommentManageService $service, $config = [])
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
        $searchModel = new CommentSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * @param int $post_id
     * @param int $id
     *
     * @return string|Response
     * @throws NotFoundHttpException
     */
    public function actionUpdate(int $post_id, int $id)
    {
        $post = $this->findModel($post_id);
        $comment = $post->getComment($id);

        $form = new CommentEditForm($comment);
        if ($form->load(Yii::$app->request->post()) && $form->validate()) {
            try {
                $this->service->edit($post->id, $comment->id, $form);

                return $this->redirect(['view', 'post_id' => $post->id, 'id' => $comment->id]);
            } catch (DomainException $e) {
                Yii::$app->errorHandler->logException($e);
                Yii::$app->session->setFlash('error', $e->getMessage());
            }
        }

        return $this->render('update', [
            'post' => $post,
            'model' => $form,
            'comment' => $comment,
        ]);
    }

    /**
     * @param int $id
     *
     * @return Post
     * @throws NotFoundHttpException
     */
    private function findModel(int $id): Post
    {
        if (($model = Post::findOne($id)) !== null) {
            return $model;
        }
        throw new NotFoundHttpException('The requested page does not exist.');
    }

    /**
     * @param int $post_id
     * @param int $id
     *
     * @return string
     * @throws NotFoundHttpException
     */
    public function actionView(int $post_id, int $id): string
    {
        $post = $this->findModel($post_id);
        $comment = $post->getComment($id);

        return $this->render('view', [
            'post' => $post,
            'comment' => $comment,
        ]);
    }

    /**
     * @param int $post_id
     * @param int $id
     *
     * @return Response
     * @throws NotFoundHttpException
     */
    public function actionActivate(int $post_id, int $id): Response
    {
        $post = $this->findModel($post_id);
        try {
            $this->service->activate($post->id, $id);
        } catch (DomainException $e) {
            Yii::$app->session->setFlash('error', $e->getMessage());
        }

        return $this->redirect(['view', 'post_id' => $post_id, 'id' => $id]);
    }

    /**
     * @param int $post_id
     * @param int $id
     *
     * @return Response
     * @throws NotFoundHttpException
     */
    public function actionDelete(int $post_id, int $id): Response
    {
        $post = $this->findModel($post_id);
        try {
            $this->service->remove($post->id, $id);
        } catch (DomainException $e) {
            Yii::$app->session->setFlash('error', $e->getMessage());
        }

        return $this->redirect(['index']);
    }
}
