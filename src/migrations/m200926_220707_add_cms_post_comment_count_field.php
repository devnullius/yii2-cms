<?php

use yii\db\Migration;

class m200926_220707_add_cms_post_comment_count_field extends Migration
{
    public function safeUp()
    {
        $this->addColumn('{{%cms_post}}', 'comments_count', $this->bigInteger()->notNull());

        $this->update('{{%cms_post}}', ['comments_count' => 0]);
    }

    public function safeDown()
    {
        $this->dropColumn('{{%cms_post}}', 'comments_count');
    }
}
