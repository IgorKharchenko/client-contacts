<?php

use app\components\migration\Migration;

/**
 * Class m181210_182925_change_foreign_keys_in_client_contact_table
 */
class m181210_182925_change_foreign_keys_in_client_contact_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp ()
    {
        $this->dropForeignKey('FK_client_contact-contact_id', 'client_contact');
        $this->dropColumn('client_contact', 'contact_id');

        $this->addColumn('client_contact', 'contact_type', $this->string(50)->notNull());
        $this->addForeignKeyAuto('client_contact', 'contact_type', 'contact_type', 'type');

        $this->dropTable('contact');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown ()
    {
        $this->createTable('contact', [
            'id'      => $this->primaryKey(),
            'type'    => $this->string(30)->notNull(),
            'content' => $this->text()->notNull(),
        ]);

        $this->dropForeignKey('FK_client_contact-contact_type', 'client_contact');
        $this->dropColumn('client_contact', 'contact_type');

        $this->addColumn('client_contact', 'contact_id', $this->integer()->notNull());
        $this->addForeignKeyAuto('client_contact', 'contact_id', 'contact', 'id');
    }
}
