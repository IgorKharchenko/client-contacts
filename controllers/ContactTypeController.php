<?php

namespace app\controllers;

use app\components\Controller;
use app\models\ContactType;
use Yii;
use yii\data\ActiveDataProvider;
use yii\filters\VerbFilter;
use yii\web\NotFoundHttpException;

/**
 * ContactTypeController implements the CRUD actions for ContactType model.
 */
class ContactTypeController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors ()
    {
        return [
            'verbs' => [
                'class'   => VerbFilter::class,
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all ContactType models.
     *
     * @return mixed
     */
    public function actionIndex ()
    {
        $dataProvider = new ActiveDataProvider([
            'query'      => ContactType::find(),
            'pagination' => [
                'pageSize' => 10,
            ],
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single ContactType model.
     *
     * @param string $id
     *
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView ($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new ContactType model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     *
     * @return mixed
     */
    public function actionCreate ()
    {
        $model = new ContactType();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('success', "Тип контакта '{$model->type}' успешно создан.");
            return $this->redirect(['index']);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing ContactType model.
     * If update is successful, the browser will be redirected to the 'view' page.
     *
     * @param string $id
     *
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate ($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('success', "Тип контакта '{$model->type}' успешно обновлён.");
            return $this->redirect(['index']);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Удаляет тип контакта.
     *
     * @param string $type тип контакта.
     *
     * @return string
     */
    public function actionDelete ($type)
    {
        $model = ContactType::findOne($type);

        $clientContacts = $model->getClientContacts()->all();
        if (!empty($clientContacts)) {
            $errorMessage = $this->renderPartial('_clientsUsingThisType', [
                'clientContacts' => $clientContacts,
            ]);

            Yii::$app->session->setFlash('error', $errorMessage);
            return $this->redirect(['index']);
        }

        $transaction = Yii::$app->db->beginTransaction();
        try {
            $model->delete();
            $transaction->commit();
        } catch (\Exception $e) {
            $transaction->rollBack();

            $errorMessage = 'Возникла ошибка при удалении.';
            Yii::$app->session->setFlash('error', $errorMessage);
            return $this->redirect(['index']);
        }

        Yii::$app->session->setFlash('success', 'Тип контакта успешно удалён.');
        return $this->redirect(['index']);
    }

    /**
     * Finds the ContactType model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     *
     * @param string $id
     *
     * @return ContactType the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel ($id)
    {
        if (($model = ContactType::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
