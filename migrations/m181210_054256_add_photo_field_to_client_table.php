<?php

use yii\db\Migration;

/**
 * Class m181210_054256_add_photo_field_to_client_table
 */
class m181210_054256_add_photo_field_to_client_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp ()
    {
        $this->addColumn('client', 'photo', $this->text()->notNull());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown ()
    {
        $this->dropColumn('client', 'photo');
    }
}
