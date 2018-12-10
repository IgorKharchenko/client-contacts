<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "client_contact".
 *
 * @property int     $id
 * @property int     $client_id
 * @property int     $contact_id
 *
 * @property Client  $client
 * @property Contact $contact
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
     * {@inheritdoc}
     */
    public function rules ()
    {
        return [
            [['client_id', 'contact_id'], 'required'],
            [['client_id', 'contact_id'], 'integer'],
            [
                ['client_id'],
                'exist',
                'skipOnError'     => true,
                'targetClass'     => Client::class,
                'targetAttribute' => ['client_id' => 'id'],
            ],
            [
                ['contact_id'],
                'exist',
                'skipOnError'     => true,
                'targetClass'     => Contact::class,
                'targetAttribute' => ['contact_id' => 'id'],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels ()
    {
        return [
            'id'         => 'ID',
            'client_id'  => 'Client ID',
            'contact_id' => 'Contact ID',
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
    public function getContact ()
    {
        return $this->hasOne(Contact::class, ['id' => 'contact_id']);
    }
}
