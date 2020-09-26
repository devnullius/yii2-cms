<?php

use yii\db\Migration;

class m200926_220001_create_cms_tag_table extends Migration
{
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%cms_tag}}', [
            'id' => $this->bigPrimaryKey(),
            'name' => $this->string()->notNull(),
            'slug' => $this->string()->notNull(),
        ], $tableOptions);

        $this->createIndex('{{%idx-cms_tag-slug}}', '{{%cms_tag}}', 'slug', true);
    }

    public function safeDown()
    {
        $this->dropTable('{{%cms_tag}}');
    }
}
