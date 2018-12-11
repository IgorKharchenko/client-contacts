<?php

namespace app\models;

use app\components\db\ActiveRecord;
use Yii;

/**
 * This is the model class for table "client_contact".
 *
 * @property int         $id
 * @property int         $client_id
 * @property string      $contact_type
 * @property string      $content
 *
 * @property Client      $client
 * @property ContactType $contactType
 */
class ClientContact extends \app\components\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName ()
    {
        return 'client_contact';
    }

    /**
     * Находит модель по его id.
     * В случае если модель не найдена возвращается null.
     *
     * @param int $id id контакта клиента
     *
     * @return \yii\db\ActiveRecord|ClientContact|null
     */
    public static function findById(int $id) :? ClientContact
    {
        return static::find()->where(['id' => $id])->one();
    }

    /**
     * @param int $clientId id клиента.
     *
     * @return array|\yii\db\ActiveRecord[]
     */
    public static function findAllByClientId (int $clientId): array
    {
        return static::find()->where(['client_id' => $clientId])->all();
    }

    /**
     * {@inheritdoc}
     */
    public function rules ()
    {
        return [
            [['client_id', 'contact_type', 'content'], 'required'],
            [['client_id'], 'integer'],
            [
                ['client_id'],
                'exist',
                'skipOnError'     => true,
                'targetClass'     => Client::class,
                'targetAttribute' => ['client_id' => 'id'],
            ],
            [['contact_type'], 'string', 'max' => 50],
            [
                ['contact_type'],
                'exist',
                'skipOnError'     => true,
                'targetClass'     => ContactType::class,
                'targetAttribute' => ['contact_type' => 'type'],
            ],
            [['content'], 'string'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels ()
    {
        return [
            'contact_type' => 'Тип',
            'content'      => 'Контакт',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getClient ()
    {
        return $this->hasOne(Client::class, ['id' => 'client_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getContactType ()
    {
        return $this->hasOne(ContactType::class, ['type' => 'contact_type']);
    }
}
