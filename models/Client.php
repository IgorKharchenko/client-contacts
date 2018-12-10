<?php

namespace app\models;

/**
 * This is the model class for table "client".
 *
 * @property int             $id
 * @property string          $name
 * @property string          $surname
 * @property string          $patronymic
 * @property string          $type
 * @property int             $active
 * @property string          $created_at
 * @property string          $updated_at
 *
 * @property ClientContact[] $clientContacts
 */
class Client extends \app\components\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName ()
    {
        return 'client';
    }

    /**
     * {@inheritdoc}
     */
    public function rules ()
    {
        return [
            [['name', 'surname'], 'required'],
            [['active'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['name', 'surname', 'patronymic'], 'string', 'max' => 30],
            [['type'], 'string', 'max' => 20],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels ()
    {
        return [
            'name'       => 'Имя',
            'surname'    => 'Фамилия',
            'patronymic' => 'Отчество',
            'type'       => 'Тип клиента',
            'active'     => 'Активный',
            'created_at' => 'Создан в',
            'updated_at' => 'Обновлен в',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getClientContacts ()
    {
        return $this->hasMany(ClientContact::class, ['client_id' => 'id']);
    }
}
