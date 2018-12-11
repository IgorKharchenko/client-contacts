<?php

use yii\db\Migration;
use app\models\ContactType;

/**
 * Class m181211_201806_add_template_contact_types
 */
class m181211_201806_add_template_contact_types extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp ()
    {
        $templateTypes = [
            'E-mail',
            'Телефон',
            'Skype',
        ];
        foreach ($templateTypes as $type) {
            $contactType = new ContactType([
                'type' => $type,
            ]);
            if (!$contactType->save()) {
                return false;
            }
        }

        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown ()
    {
    }
}
