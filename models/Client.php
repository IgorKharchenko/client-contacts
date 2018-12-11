<?php

namespace app\models;

use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "client".
 *
 * @property int             $id
 * @property string          $name
 * @property string          $surname
 * @property string          $patronymic
 * @property string          $type
 * @property int             $active
 * @property string          $photo
 * @property string          $created_at
 * @property string          $updated_at
 *
 * @property ClientContact[] $clientContacts
 */
class Client extends \app\components\db\ActiveRecord
{
    const TYPE_CUSTOMER = 'Покупатель';
    const TYPE_PROVIDER = 'Поставщик';
    const TYPE_PARTNER = 'Партнер';

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
            [['name', 'surname', 'type', 'active'], 'required'],
            [['active'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['name', 'surname', 'patronymic'], 'string', 'max' => 30],
            [['type'], 'string', 'max' => 20],
            [['photo'], 'string', 'max' => 255],
            [
                ['type'],
                'in',
                'range' => [
                    static::TYPE_CUSTOMER,
                    static::TYPE_PROVIDER,
                    static::TYPE_PARTNER,
                ],
            ],
        ];
    }

    /**
     * Подключаем TimestampBehavior.
     *
     * @return array
     */
    public function behaviors ()
    {
        $behaviors = parent::behaviors();
        $behaviors['TimestampBehavior'] = [
            'class'              => TimestampBehavior::class,
            'createdAtAttribute' => 'created_at',
            'updatedAtAttribute' => 'updated_at',
            'value'              => function() { return date(static::DB_DATETIME_FORMAT); },
        ];
        return $behaviors;
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels ()
    {
        return [
            'name'       => 'Имя *',
            'surname'    => 'Фамилия *',
            'patronymic' => 'Отчество',
            'type'       => 'Тип клиента *',
            'active'     => 'Активный *',
            'photo'      => 'Фотография',
            'created_at' => 'Создан в',
            'updated_at' => 'Обновлен в',
        ];
    }

    /**
     * Возвращает полное ФИО клиента.
     *
     * @return string
     */
    public function getFullName ()
    {
        return sprintf(
            '%s %s %s',
            $this->surname,
            $this->name,
            $this->patronymic
        );
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getClientContacts ()
    {
        return $this->hasMany(ClientContact::class, ['client_id' => 'id']);
    }
}
