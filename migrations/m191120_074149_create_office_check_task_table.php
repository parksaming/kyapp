<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%office_check_task}}`.
 */
class m191120_074149_create_office_check_task_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%office_check_task}}', [
            'id' => $this->primaryKey(),
            'userCode' => $this->string(45)->notNull(),
            'workId' => $this->bigInteger()->notNull(),
            'confirmTypeId' => $this->integer()->notNull(),
            'comment' => $this->string(300),
            'state' => $this->smallInteger()->notNull()->defaultValue(0),
            'createdAt' => $this->timestamp(),
            'updatedAt' => $this->timestamp()
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%office_check_task}}');
    }
}
