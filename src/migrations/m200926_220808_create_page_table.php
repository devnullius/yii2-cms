<?php

use yii\db\Migration;

class m200926_220808_create_page_table extends Migration
{
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%cms_page}}', [
            'id' => $this->primaryKey(),
            'title' => $this->string()->notNull(),
            'slug' => $this->string()->notNull(),
            'content' => $this->text(),
            'meta_json' => $this->json()->notNull(),
            'lft' => $this->integer()->notNull(),
            'rgt' => $this->integer()->notNull(),
            'depth' => $this->integer()->notNull(),
        ], $tableOptions);

        $this->createIndex('{{%idx-cms_page-slug}}', '{{%cms_page}}', 'slug', true);

        $this->insert('{{%cms_page}}', [
            'id' => 1,
            'title' => '',
            'slug' => 'root',
            'content' => null,
            'meta_json' => '{}',
            'lft' => 1,
            'rgt' => 2,
            'depth' => 0,
        ]);
    }

    public function safeDown()
    {
        $this->dropTable('{{%cms_page}}');
    }
}
