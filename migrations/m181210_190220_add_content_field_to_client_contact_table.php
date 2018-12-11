<?php

use yii\db\Migration;

/**
 * Class m181210_190220_add_content_field_to_client_contact_table
 */
class m181210_190220_add_content_field_to_client_contact_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp ()
    {
        $this->addColumn('client_contact', 'content', $this->text()->notNull());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown ()
    {
        $this->dropColumn('client_contact', 'content');
    }
}
