<?php

use yii\db\Migration;

class m200926_220505_create_cms_comment_table extends Migration
{
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%cms_comment}}', [
            'id' => $this->bigPrimaryKey(),
            'post_id' => $this->bigInteger()->notNull(),
            'user_id' => $this->bigInteger()->notNull(),
            'parent_id' => $this->bigInteger(),
            'created_at' => $this->integer()->unsigned()->notNull(),
            'text' => $this->text()->notNull(),
            'active' => $this->boolean()->notNull()->defaultValue(false),
        ], $tableOptions);

        $this->createIndex('{{%idx-cms_comment-post_id}}', '{{%cms_comment}}', 'post_id');
        $this->createIndex('{{%idx-cms_comment-user_id}}', '{{%cms_comment}}', 'user_id');
        $this->createIndex('{{%idx-cms_comment-parent_id}}', '{{%cms_comment}}', 'parent_id');

        $this->addForeignKey(
            '{{%fk-cms_comment-post_id}}',
            '{{%cms_comment}}',
            'post_id',
            '{{%cms_post}}',
            'id',
            'CASCADE'
        );
        $this->addForeignKey(
            '{{%fk-cms_comment-user_id}}',
            '{{%cms_comment}}',
            'user_id',
            '{{%user}}',
            'id',
            'CASCADE'
        );
        $this->addForeignKey(
            '{{%fk-cms_comment-parent_id}}',
            '{{%cms_comment}}',
            'parent_id',
            '{{%cms_comment}}',
            'id',
            'CASCADE'
        );
    }

    public function safeDown()
    {
        $this->dropTable('{{%cms_comment}}');
    }
}
