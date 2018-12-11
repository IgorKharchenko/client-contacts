<?php

namespace app\models;

use Yii;
use yii\base\Exception;
use yii\log\Logger;

class ClientContactsSaveForm
{
    private $contacts;

    /**
     * ClientContactsSaveForm constructor.
     *
     * @param array $contacts контакты.
     */
    public function __construct (array $contacts)
    {
        $this->contacts = $contacts;
    }

    /**
     * Схороняет все контакты.
     *
     * @return void
     *
     * @throws Exception в случае ошибки при схоронении контакта.
     */
    public function saveContacts ()
    {
        /** @var array $contactAttributes */
        foreach ($this->contacts as $contactAttributes) {
            $contact = null;

            // если есть id то подгружаем; если нет то создаём новый контакт
            if (!empty($contactAttributes['id'])) {
                $contact = ClientContact::findById((int)$contactAttributes['id']);
                if (null === $contact) {
                    Yii::getLogger()
                       ->log('Ошибка при схоронении контакта: контакт пустой при существующем id', Logger::LEVEL_ERROR);
                    throw new Exception('Ошибка при сохранении контакта');
                }

                $contact->setAttributes($contactAttributes);
            } else {
                $contact = new ClientContact($contactAttributes);
                $contact->id = (int)$contact->id;
                $contact->client_id = (int)$contact->client_id;
            }

            if (!$contact->save()) {
                Yii::getLogger()->log('Ошибка при схоронении контакта', Logger::LEVEL_ERROR);
                throw new Exception('Ошибка при сохранении контакта');
            }
        }
    }
}