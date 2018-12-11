<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "contact_type".
 *
 * @property string          $type
 *
 * @property ClientContact[] $clientContacts
 */
class ContactType extends \app\components\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName ()
    {
        return 'contact_type';
    }

    /**
     * @return string[]
     */
    public static function getAllTypesAsArray (): array
    {
        $contactTypes = static::find()->all();

        return array_map(function(ContactType $contactType) {
            return $contactType->type;
        }, $contactTypes);
    }

    /**
     * {@inheritdoc}
     */
    public function rules ()
    {
        return [
            [['type'], 'required'],
            [['type'], 'string', 'max' => 50],
            [['type'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels ()
    {
        return [
            'type' => 'Тип контакта',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getClientContacts ()
    {
        return $this->hasMany(ClientContact::class, ['contact_type' => 'type']);
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
            'Google+',
        ];
    }
}
