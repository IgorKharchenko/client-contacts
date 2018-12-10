<?php

namespace app\models;

/**
 * This is the model class for table "contact".
 *
 * @property int             $id
 * @property string          $type
 * @property string          $content
 *
 * @property ClientContact[] $clientContacts
 */
class Contact extends \app\components\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName ()
    {
        return 'contact';
    }

    /**
     * {@inheritdoc}
     */
    public function behaviors ()
    {
        $behaviors = parent::behaviors();
        unset ($behaviors['TimestampBehavior']);
        return $behaviors;
    }

    /**
     * {@inheritdoc}
     */
    public function rules ()
    {
        return [
            [['type', 'content'], 'required'],
            [['content'], 'string'],
            [['type'], 'string', 'max' => 30],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels ()
    {
        return [
            'type'    => 'Тип',
            'content' => 'Контакт',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getClientContacts ()
    {
        return $this->hasMany(ClientContact::class, ['contact_id' => 'id']);
    }

    /**
     * Возвращает типы контактов.
     *
     * @return array
     */
    public function getAvailableTypes (): array
    {
        return [
            'Телефон',
            'Email',
            'Skype',
            'Instagram',
            'YouTube',
        ];
    }
}
