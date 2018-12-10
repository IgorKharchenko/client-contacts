<?php

use app\components\migration\Migration;

/**
 * Handles the creation of table `contact`.
 */
class m181210_035623_create_contact_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp ()
    {
        $this->createTable('contact', [
            'id'      => $this->primaryKey(),
            'type'    => $this->string(30)->notNull(),
            'content' => $this->text()->notNull(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown ()
    {
        $this->dropTable('contact');
    }
}
