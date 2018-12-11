<?php

namespace app\models;

use yii\base\Exception;

class ClientContactsRemoveForm
{
    private $contactsIds;
    private $clientId;

    /**
     * ClientContactRemoveForm constructor.
     *
     * @param array $contactsIds id-шники удалённых контактов клиента.
     * @param int   $clientId    id клиента.
     */
    public function __construct (array $contactsIds, int $clientId)
    {
        $this->contactsIds = array_map(function($contactId) {
            return (int)$contactId;
        }, $contactsIds);

        $this->clientId = $clientId;
    }

    /**
     * Удаляет контакты, помеченные на фронтэнде как удалённые.
     *
     * @return void
     *
     * @throws Exception в случае ошибки удаления.
     */
    public function deleteContacts ()
    {
        // пробегаемся по всем контактам
        // и выясняем какие были удалены на фронте
        $clientContacts = ClientContact::find()
                                       ->where(['client_id' => $this->clientId])
                                       ->all();
        /** @var ClientContact $clientContact */
        foreach ($clientContacts as $clientContact) {
            // проверяем на случай несуществующих клиентов
            if (!in_array($clientContact->id, $this->contactsIds, true)) {
                continue;
            }

            // если контакт был удалён на фронте, удаляем его на бэке
            $clientContact->delete();
        }
    }
}