<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%user}}`.
 */
class m201204_094621_create_user_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%user}}', [
            'id' => $this->primaryKey(),
            'username' => $this->char(50)->notNull()->unique(),
            'password' => $this->char(255)->notNull(),
            'email' => $this->char(50),
            'phone' => $this->char(20),
            'access_token' => $this->string(514),
            'auth_key' => $this->char(100),
            'status' => $this->integer()->defaultValue(1),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer(),
            'created_by' => $this->integer(),
            'updated_at' => $this->integer(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%user}}');
    }
}
