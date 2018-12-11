<?php

namespace app\controllers;

use app\components\Controller;
use app\models\Client;
use app\models\ClientContact;
use app\models\ClientContactsRemoveForm;
use app\models\ClientContactsSaveForm;
use Yii;
use yii\base\Exception;
use yii\log\Logger;

/**
 * Class ContactController
 *
 * @package app\controllers
 */
class ContactController extends Controller
{
    /**
     * REST-экшон.
     * Сохраняет контакты клиента.
     * 200: success
     * 500: ошибка при схоронении / удалении контакта
     *
     * @return array
     */
    public function actionSaveContactsAjax ()
    {
        $post = Yii::$app->request->post();

        $contacts = $post['contacts'];
        $removedContactsIds = $post['removedContactsIds'];
        $clientId = (int)$post['clientId'];

        $transaction = Yii::$app->db->beginTransaction();

        if (!empty($removedContactsIds)) {
            try {
                $form = new ClientContactsRemoveForm($removedContactsIds, $clientId);
                $form->deleteContacts();
            } catch (\Exception $e) {
                $transaction->rollBack();
                \Yii::getLogger()
                    ->log('Ошибка при удалении контакта, который был удалён на фронте', Logger::LEVEL_ERROR);

                return $this->handleAjaxResponse(500, null, 'Произошла ошибка при удалении сохраняемого контакта.');
            }

            // убираем все удаленные контакты из списка контактов
            // (не знаю, куда эту логику вынести)
            $contacts = array_filter($contacts, function (array $contact) use ($removedContactsIds) {
                return !in_array($contact['id'], $removedContactsIds, true);
            });
        }

        try {
            $form = new ClientContactsSaveForm($contacts);
            $form->saveContacts();
        } catch (Exception $e) {
            $transaction->rollBack();

            return $this->handleAjaxResponse(500, null, 'Произошла ошибка при сохранении контакта.');
        }

        $transaction->commit();
        return $this->handleAjaxResponse(200);
    }
}
