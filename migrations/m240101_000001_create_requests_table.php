<?php

use yii\db\Migration;

class m240101_000001_create_requests_table extends Migration
{
    public function safeUp()
    {
        $this->createTable('{{%requests}}', [
            'id'         => $this->primaryKey(),
            'user_id'    => $this->integer()->notNull(),
            'amount'     => $this->integer()->notNull(),
            'term'       => $this->integer()->notNull(),
            'status'     => $this->string(16)->notNull()->defaultValue('pending'),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
        ]);

        $this->createIndex('idx_requests_user', '{{%requests}}', 'user_id');
        $this->createIndex('idx_requests_status', '{{%requests}}', 'status');
        $this->createIndex('idx_requests_user_status', '{{%requests}}', ['user_id','status']);
    }

    public function safeDown()
    {
        $this->dropTable('{{%requests}}');
    }
}
