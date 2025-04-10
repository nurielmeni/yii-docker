<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%team}}`.
 */
class m210522_210041_create_team_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%team}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string(64),
            'title' => $this->string(),
            'body' => $this->text(),
            'image' => $this->string(256),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%team}}');
    }
}
