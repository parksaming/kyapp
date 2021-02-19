<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%self_check_task}}`.
 */
class m191120_074213_create_self_check_task_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%self_check_task}}', [
            'id' => $this->primaryKey(),
            'userCode' => $this->string(45)->notNull(),
            'workId' => $this->bigInteger()->notNull(),
            'workTypeId' => $this->integer()->notNull(),
            'state' => $this->smallInteger()->notNull()->defaultValue(0),
            'createdAt' => $this->timestamp(),
            'updatedAt' => $this->timestamp(),
            'isAutomatic' => $this->smallInteger()
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%self_check_task}}');
    }
}
