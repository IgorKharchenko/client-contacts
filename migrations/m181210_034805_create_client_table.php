<?php

use app\components\migration\Migration;

/**
 * Handles the creation of table `client`.
 */
class m181210_034805_create_client_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp ()
    {
        $this->createTableWithTimestamps('client', [
            'id'         => $this->primaryKey(),
            'name'       => $this->string(30)->notNull(),
            'surname'    => $this->string(30)->notNull(),
            'patronymic' => $this->string(30),
            'type'       => $this->string(20),
            'active'     => $this->integer(1),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown ()
    {
        $this->dropTable('client');
    }
}
