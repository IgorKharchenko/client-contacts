<?php

use app\components\migration\Migration;

/**
 * Class m181211_200742_create_index_to_client_contact
 */
class m181211_200742_create_index_to_client_contact extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp ()
    {
        $this->createIndex(
            'idx_client_contact_content',
            'client_contact',
            ['content(70)', 'client_id', 'contact_type']
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown ()
    {
        $this->dropIndex('idx_client_contact_content', 'client_contact');
    }
}
