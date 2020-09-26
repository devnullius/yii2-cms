<?php

use yii\db\Migration;

class m200926_220202_create_cms_category_table extends Migration
{
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%cms_category}}', [
            'id' => $this->bigPrimaryKey(),
            'name' => $this->string()->notNull(),
            'slug' => $this->string()->notNull(),
            'title' => $this->string(),
            'description' => $this->text(),
            'sort' => $this->integer()->notNull(),
            'meta_json' => $this->json()->notNull(),
        ], $tableOptions);

        $this->createIndex('{{%idx-cms_category-slug}}', '{{%cms_category}}', 'slug', true);
    }

    public function safeDown()
    {
        $this->dropTable('{{%cms_category}}');
    }
}
