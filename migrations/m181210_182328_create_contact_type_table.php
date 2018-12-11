<?php

use yii\db\Migration;

/**
 * Handles the creation of table `contact_type`.
 */
class m181210_182328_create_contact_type_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('contact_type', [
            'type' => $this->string(50)->notNull(),
        ]);

        $this->addPrimaryKey('PK_type', 'contact_type', 'type');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('contact_type');
    }
}
