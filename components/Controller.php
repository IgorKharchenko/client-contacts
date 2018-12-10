<?php

namespace app\components;

use yii\base\InvalidArgumentException;
use yii\web\Controller as BaseController;
use yii\web\Response;
use yii\bootstrap\ActiveForm;
use app\components\db\ActiveRecord as BaseActiveRecord;
use Yii;
use yii\base\ExitException;

/**
 * Class Controller
 *
 * @package common\components
 */
class Controller extends BaseController
{
    /**
     * Выполняет ajax-валидацию, если запрос = ajax.
     *
     * @param BaseActiveRecord $model
     *
     * @return void
     *
     * @throws ExitException
     */
    protected function performAjaxValidation ($model)
    {
        if (!Yii::$app->request->isAjax) {
            return;
        }

        $response = Yii::$app->response;
        $response->format = Response::FORMAT_JSON;

        if ($model->load(Yii::$app->request->post())) {
            $response->statusCode = 200;
            echo json_encode(ActiveForm::validate($model));
        } else {
            $response->statusCode = 400;
        }

        Yii::$app->end();
    }

    /**
     * Отправляет ajax-ответ в виде json-объекта.
     * Объект выглядит вот так:
     * {"success": true,  "data": "данные", "error": null}
     * {"success": false, "data": null,     "error": "сообщение об ошибке"}
     *
     * @param int               $statusCode
     * @param array|string|null $data
     * @param string|null       $error
     *
     * @return array
     *
     * @throws InvalidArgumentException если статус-код ответа невалидный.
     */
    protected function handleAjaxResponse ($statusCode, $data = null, $error = null)
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        $statusCode = (int)$statusCode;
        Yii::$app->response->setStatusCode($statusCode);

        $out = [
            'success' => $statusCode === 200,
        ];
        if (null !== $data) {
            $out['data'] = $data;
        }
        if (null !== $error) {
            $out['error'] = $error;
        }

        return $out;
    }
}