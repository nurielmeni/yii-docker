<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%campaign}}`.
 */
class m200708_180612_add_hits_column_apply_column_to_campaign_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%campaign}}', 'hits', $this->integer());
        $this->addColumn('{{%campaign}}', 'apply', $this->integer());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%campaign}}', 'hits');
        $this->dropColumn('{{%campaign}}', 'apply');
    }
}
