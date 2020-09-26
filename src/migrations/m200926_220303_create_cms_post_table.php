<?php

use yii\db\Migration;

class m200926_220303_create_cms_post_table extends Migration
{
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%cms_post}}', [
            'id' => $this->bigPrimaryKey(),
            'category_id' => $this->bigInteger()->notNull(),
            'created_at' => $this->integer()->unsigned()->notNull(),
            'title' => $this->string()->notNull(),
            'description' => $this->text(),
            'content' => $this->text(),
            'photo' => $this->string(),
            'status' => $this->boolean()->notNull()->defaultValue(false),
            'meta_json' => $this->json()->notNull(),
        ], $tableOptions);

        $this->createIndex('{{%idx-cms_post-category_id}}', '{{%cms_post}}', 'category_id');

        $this->addForeignKey(
            '{{%fk-cms_post-category_id}}',
            '{{%cms_post}}',
            'category_id',
            '{{%cms_category}}',
            'id'
        );
    }

    public function safeDown()
    {
        $this->dropTable('{{%cms_post}}');
    }
}
