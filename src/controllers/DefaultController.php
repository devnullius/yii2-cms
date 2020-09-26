<?php
declare(strict_types=1);

namespace devnullius\user\controllers;

use devnullius\helper\actions\CreateExtAction;
use devnullius\helper\actions\DeleteExtAction;
use devnullius\helper\actions\IndexExtAction;
use devnullius\user\entities\User;
use devnullius\user\forms\UserCreateForm;
use devnullius\user\forms\UserEditForm;
use devnullius\user\search\UserSearch;
use devnullius\user\useCases\UserManageService;
use DomainException;
use Yii;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

final class DefaultController extends Controller
{
    private UserManageService $service;

    public function __construct($id, $module, UserManageService $service, $config = [])
    {
        parent::__construct($id, $module, $config);
        $this->service = $service;
    }

    /**
     * @inheritdoc
     */
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

    public function actions(): array
    {
        $actions = [
            'index' => [
                'class' => IndexExtAction::class,
                'searchModel' => UserSearch::class,
            ],
            'create' => [
                'class' => CreateExtAction::class,
                'form' => UserCreateForm::class,
                'service' => $this->service,
                'redirectView' => 'index',
                'view' => 'create',
            ],
            'delete' => [
                'class' => DeleteExtAction::class,
                'service' => $this->service,
            ],
        ];

        return ArrayHelper::merge(parent::actions(), $actions);
    }

    public function actionView($id): string
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    private function findModel($id)
    {
        if (($model = User::findOne($id)) !== null) {
            return $model;
        }
        throw new NotFoundHttpException('The requested page does not exist.');
    }

    public function actionUpdate($id)
    {
        $user = $this->findModel($id);

        $form = new UserEditForm($user);
        if ($form->load(Yii::$app->request->post()) && $form->validate()) {
            try {
                $this->service->edit($user->id, $form);

                return $this->redirect(['view', 'id' => $user->id]);
            } catch (DomainException $e) {
                Yii::$app->errorHandler->logException($e);
                Yii::$app->session->setFlash('error', $e->getMessage());
            }
        }

        return $this->render('update', [
            'model' => $form,
            'user' => $user,
        ]);
    }
}
