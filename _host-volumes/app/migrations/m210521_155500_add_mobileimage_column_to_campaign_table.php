<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%campaign}}`.
 */
class m210521_155500_add_mobileimage_column_to_campaign_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%campaign}}', 'mobile_image', $this->string(256));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%campaign}}', 'mobile_image');
    }
}
