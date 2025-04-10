<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%campaign}}`.
 */
class m210521_215800_add_youtube_video_id_column_to_campaign_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%campaign}}', 'youtube_video_id', $this->string(256));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%campaign}}', 'youtube_video_id');
    }
}
