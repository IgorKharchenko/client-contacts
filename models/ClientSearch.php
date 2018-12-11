<?php

namespace app\models;

use yii\data\ActiveDataProvider;
use yii\db\ActiveQuery;
use yii\db\Query;

class ClientSearch extends Client
{
    const CLIENT_ACTIVENESS_ACTIVE = 'active';
    const CLIENT_ACTIVENESS_INACTIVE = 'inactive';
    const CLIENT_ACTIVENESS_ALL = 'all';

    public $fullName;
    public $contact;

    public $typeOfActiveness;
    public $typesOfContact;
    public $typeOfClient;

    public function rules ()
    {
        return [
            [
                ['fullName', 'contact', 'typeOfActiveness', 'typeOfClient'],
                'string',
            ],
            [
                ['fullName', 'contact', 'typeOfActiveness', 'typeOfClient', 'typesOfContact'],
                'safe',
            ],
        ];
    }

    public function attributeLabels ()
    {
        return [
            'fullName'         => 'ФИО',
            'typeOfActiveness' => 'Признак активности клиента',
            'contact'          => 'Контакт',
            'typesOfContact'   => 'Тип контакта',
            'typeOfClient'     => 'Тип клиента',
        ];
    }

    /**
     * Поиск клиентов по полям.
     *
     * @return ActiveDataProvider
     */
    public function search ()
    {
        $query = Client::find();

        $dataProvider = new ActiveDataProvider([
            'query'      => $query,
            'pagination' => [
                'pageSize' => 10,
            ],
        ]);

        $this->load(\Yii::$app->request->post());

        if (!$this->validate()) {
            return $dataProvider;
        }

        if (!empty($this->fullName)) {
            $query->andFilterWhere([
                'like',
                'concat(client.surname, " " , client.name, " ", client.patronymic) ',
                $this->fullName,
            ]);
        }

        $query = $this->andFilterContacts($query);
        $query = $this->andFilterActiveness($query);

        if (!empty($this->typeOfClient)) {
            $query->andFilterWhere([
                'like',
                'client.type',
                $this->typeOfClient,
            ]);
        }

        return $dataProvider;
    }

    /**
     * Добавляет условия для фильтрации по контактам.
     * Вынес отдельно, пушо код функции search() уже в портянку превратился
     *
     * @todo проверить like на оптимальность
     *
     * @param ActiveQuery $query запрос
     *
     * @return ActiveQuery запрос с фильтром по контактам
     */
    private function andFilterContacts (ActiveQuery $query)
    {
        if (!empty($this->contact) || !empty($this->typesOfContact)) {
            $query->joinWith('clientContacts');
        }
        if (!empty($this->contact)) {
            $query->andFilterWhere([
                'like',
                'client_contact.content',
                $this->contact,
            ]);
        }
        if (!empty($this->typesOfContact)) {
            $query->andFilterWhere([
                'in',
                'client_contact.contact_type',
                $this->typesOfContact,
            ]);
        }

        return $query;
    }

    /**
     * Фильтр по активности клиента (неактивен, активен, все клиенты).
     *
     * @param ActiveQuery $query
     *
     * @return ActiveQuery
     */
    public function andFilterActiveness (ActiveQuery $query)
    {
        if (!empty($this->typeOfActiveness) && 'all' !== $this->typeOfActiveness) {
            $query->andFilterWhere([
                '=',
                'client.active',
                static::CLIENT_ACTIVENESS_ACTIVE === $this->typeOfActiveness ? 1 : 0,
            ]);
        }

        return $query;
    }
}