<?php

use yii\db\Migration;

class m200926_220404_create_cms_tag_assignment_table extends Migration
{
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%cms_tag_assignment}}', [
            'post_id' => $this->bigInteger()->notNull(),
            'tag_id' => $this->bigInteger()->notNull(),
        ], $tableOptions);

        $this->addPrimaryKey('{{%pk-cms_tag_assignment}}', '{{%cms_tag_assignment}}', ['post_id', 'tag_id']);

        $this->createIndex('{{%idx-cms_tag_assignment-post_id}}', '{{%cms_tag_assignment}}', 'post_id');
        $this->createIndex('{{%idx-cms_tag_assignment-tag_id}}', '{{%cms_tag_assignment}}', 'tag_id');

        $this->addForeignKey(
            '{{%fk-cms_tag_assignment-post_id}}',
            '{{%cms_tag_assignment}}',
            'post_id',
            '{{%cms_post}}',
            'id',
            'CASCADE',
            'RESTRICT'
        );
        $this->addForeignKey(
            '{{%fk-cms_tag_assignment-tag_id}}',
            '{{%cms_tag_assignment}}',
            'tag_id',
            '{{%cms_tag}}',
            'id',
            'CASCADE',
            'RESTRICT'
        );
    }

    public function safeDown()
    {
        $this->dropTable('{{%cms_tag_assignment}}');
    }
}
