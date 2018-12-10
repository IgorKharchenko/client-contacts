<?php

use app\components\migration\Migration;

/**
 * Handles the creation of table `client_contact`.
 */
class m181210_035745_create_client_contact_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp ()
    {
        $this->createTable('client_contact', [
            'id'         => $this->primaryKey(),
            'client_id'  => $this->integer()->notNull(),
            'contact_id' => $this->integer()->notNull(),
        ]);

        $this->addForeignKeyAuto('client_contact', 'client_id', 'client', 'id');
        $this->addForeignKeyAuto('client_contact', 'contact_id', 'contact', 'id');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown ()
    {
        $this->dropForeignKey('FK_client_contact-client_id', 'client_contact');
        $this->dropForeignKey('FK_client_contact-contact_id', 'client_contact');

        $this->dropTable('client_contact');
    }
}
