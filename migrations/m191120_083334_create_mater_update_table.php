<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%mater_update}}`.
 */
class m191120_083334_create_mater_update_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%master_update}}', [
            'id' => $this->primaryKey(),
            'key' => $this->string(100)->notNull(),
            'updateTime' => $this->timestamp()
        ]);
        $this->insert('master_update',['key' => 'recommend']);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%master_update}}');
    }
}
