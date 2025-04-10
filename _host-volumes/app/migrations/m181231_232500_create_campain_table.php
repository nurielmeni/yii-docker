<?php

use yii\db\Migration;

/**
 * Class m181231_232500_create_campain_table
 */
class m181231_232500_create_campain_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        if ($this->db->getTableSchema('campaign', true) === null){
            $this->createTable('campaign', [
                'id' => $this->primaryKey(),
                'fbf' => $this->integer(1),
                'name' => $this->string(64)->notNull(),
                'start_date' => $this->integer(),
                'end_date' => $this->integer(),
                'campaign' => $this->string(1024),
                'image' => $this->string(256),
                'logo' => $this->string(256),
                'sid' => $this->string(64),
                'show_store' => $this->integer(1),
                'show_cv' => $this->integer(1),
                'button_color' => $this->string(16),
                'contact' => $this->string(30),
                'tag_header' => $this->string(2048),
                'tag_body' => $this->string(2048),
            ]);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m181231_232500_create_campain_table cannot be reverted.\n";
        $this->dropTable('campaign');
    }
    
}
