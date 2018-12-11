<?php

namespace app\controllers;

use app\components\view\dropDownList\ArrayFormatter;
use app\models\ClientContact;
use app\models\ClientSearch;
use app\models\ContactType;
use app\models\UploadPhotoForm;
use Yii;
use app\models\Client;
use yii\base\InvalidArgumentException;
use yii\data\ActiveDataProvider;
use app\components\Controller;
use yii\helpers\ArrayHelper;
use yii\rbac\PhpManager;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;

/**
 * ClientController implements the CRUD actions for Client model.
 */
class ClientController extends Controller
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
                    'delete'    => ['POST'],
                    'get-by-id' => ['GET'],
                ],
            ],
        ];
    }

    /**
     * Lists all Client models.
     *
     * @return mixed
     */
    public function actionIndex ()
    {
        $post = Yii::$app->request->post();
        $clientSearch = new ClientSearch();

        $dataProvider = new ActiveDataProvider([
            'query'      => Client::find(),
            'pagination' => [
                'pageSize' => 10,
            ],
        ]);

        if ($clientSearch->load($post)) {
            $dataProvider = $clientSearch->search();
        }

        $clientTypes = ArrayFormatter::formatArray([
            Client::TYPE_CUSTOMER,
            Client::TYPE_PROVIDER,
            Client::TYPE_PARTNER,
        ]);
        $contactTypes = ArrayFormatter::formatArray(ContactType::getAllTypesAsArray());

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'clientSearch' => $clientSearch,
            'client'       => new Client(),
            'clientTypes'  => $clientTypes,
            'contactTypes' => $contactTypes,
        ]);
    }

    /**
     * Displays a single Client model.
     *
     * @param integer $id
     *
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView ($id)
    {
        $clientTypes = ArrayFormatter::formatArray([
            Client::TYPE_CUSTOMER,
            Client::TYPE_PROVIDER,
            Client::TYPE_PARTNER,
        ]);

        $contacts = ClientContact::findAllByClientId($id);

        return $this->render('view', [
            'model'       => $this->findModel($id),
            'clientTypes' => $clientTypes,
            'contacts'    => $contacts,
        ]);
    }

    /**
     * Creates a new Client model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     *
     * @return mixed
     */
    public function actionCreate ()
    {
        $model = new Client();

        return $this->createOrUpdate('create', $model);
    }

    /**
     * Updates an existing Client model.
     * If update is successful, the browser will be redirected to the 'view' page.
     *
     * @param integer $id
     *
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate ($id)
    {
        $model = $this->findModel($id);

        return $this->createOrUpdate('update', $model);
    }

    /**
     * Картина маслом: "когда пытаешься сделать тонкий контроллер"
     *
     * @param string $mode
     * @param Client $model
     *
     * @return string
     *
     * @throws InvalidArgumentException если $mode не create или update
     */
    private function createOrUpdate ($mode = 'create', Client $model)
    {
        if (!in_array($mode, ['create', 'update'])) {
            throw new InvalidArgumentException('Mode must be "create" or "update"');
        }

        $successMessage = '';
        if ($model->isNewRecord) {
            $successMessage = 'Клиент успешно создан.';
        } else {
            $successMessage = 'Клиент успешно обновлен.';
        }

        $oldPhoto = $model->photo;

        if ($model->load(Yii::$app->request->post())) {
            $uploadForm = new UploadPhotoForm();
            $uploadForm->imageFile = UploadedFile::getInstance($model, 'photo');

            if (!$model->isNewRecord) {
                $uploadForm->imageUrl = $oldPhoto;
            }

            // при создании клиента фотография загружается
            // при обновлении клиента фотография заменяется на новую
            // либо же остаётся прежней, если в браузере клиент не выбрал фотографию
            $isPhotoReuploaded = !$model->getIsNewRecord() &&
                null !== $uploadForm->imageFile;

            // если фотография не загружена, то оставляем предыдущую
            if (!$isPhotoReuploaded) {
                $model->photo = $oldPhoto;
            }

            if ($isPhotoReuploaded || $model->getIsNewRecord()) {
                if ($uploadForm->upload()) {
                    $model->photo = $uploadForm->imageUrl;
                } else {
                    $model->addError('photo', 'Не удалось загрузить фотографию.');
                    $model->photo = $oldPhoto;
                }
            }

            if ($model->save()) {
                Yii::$app->session->setFlash('success', $successMessage);
                return $this->handleAjaxResponse(200, $model->getAttributes());
            }

            return $this->handleAjaxResponse(400);
        }

        $clientTypes = ArrayFormatter::formatArray([
            Client::TYPE_CUSTOMER,
            Client::TYPE_PROVIDER,
            Client::TYPE_PARTNER,
        ]);
        $contactTypes = ArrayFormatter::formatArray(ContactType::getAllTypesAsArray());

        $contacts = [];
        if (!$model->getIsNewRecord()) {
            $contacts = ClientContact::findAllByClientId((int)$model->id);
        }

        return $this->render($mode, [
            'model'        => $model,
            'clientTypes'  => $clientTypes,
            'contactTypes' => $contactTypes,
            'contacts'     => $contacts,
        ]);
    }

    /**
     * Deletes an existing Client model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     *
     * @param integer $id
     *
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete ($id)
    {
        $this->findModel($id)->delete();

        Yii::$app->session->setFlash('success', 'Клиент успешно удалён.');

        return $this->redirect(['index']);
    }

    /**
     * Рендеринг формы контактов клиента.
     * Подгружается при редактировании и создании клиента.
     *
     * @param int|null $clientId id клиента, если null то форма отсылается пустой.
     *
     * @return string
     */
    public function actionClientContactForm ($clientId = null)
    {
        $contacts = [];
        if (null !== $clientId) {
            $contacts = ClientContact::find()
                                     ->where(['client_id' => (int)$clientId])
                                     ->all();
        }

        $contactTypes = ArrayFormatter::formatArray(ContactType::getAllTypesAsArray());

        return $this->renderPartial('//contact/_contacts', [
            'contacts'     => $contacts,
            'contactTypes' => $contactTypes,
        ]);
    }

    /**
     * REST-экшон.
     * Ответ:
     * 200 {success: true, data: {все поля класса Client}}
     * 404 {success: false, error: 'Клиент с таким id не найден'}
     *
     * @param int $clientId id клиента.
     *
     * @return array
     */
    public function actionGetById ($clientId)
    {
        $model = Client::findOne((int)$clientId);
        if (null === $model) {
            return $this->handleAjaxResponse(404, null, 'Клиент с таким id не найден');
        }

        return $this->handleAjaxResponse(200, $model->getAttributes());
    }

    /**
     * Finds the Client model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     *
     * @param integer $id
     *
     * @return Client the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel ($id)
    {
        if (($model = Client::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
