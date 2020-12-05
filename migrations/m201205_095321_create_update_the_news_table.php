<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%update_the_news}}`.
 */
class m201205_095321_create_update_the_news_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->dropColumn('news', 'created_by');
        $this->dropColumn('news', 'author');
        $this->addColumn('news', 'author_id', $this->integer());
        $this->createIndex(
            'user_table_connection_index',
            'news',
            'author_id'
        );

        $this->addForeignKey(
            'user_table_connection',
            'news',
            'author_id',
            'user',
            'id'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->addColumn('news', 'created_by', 'integer');
        $this->addColumn('news', 'author', 'integer');
        $this->dropForeignKey(
            'user_table_connection',
            'news');
        $this->dropIndex(
            'user_table_connection_index',
            'news'
        );
        $this->dropColumn('news', 'author_id');
    }
}
