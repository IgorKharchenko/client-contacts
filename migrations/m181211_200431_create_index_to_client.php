<?php

use app\components\migration\Migration;

/**
 * Class m181211_200431_create_index_to_client
 */
class m181211_200431_create_index_to_client extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createIndexAuto('client', ['surname', 'name', 'patronymic', 'type', 'active']);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropIndex('idx_client-surname', 'client');
    }
}
