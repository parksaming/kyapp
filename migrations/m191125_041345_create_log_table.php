<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%log}}`.
 */
class m191125_041345_create_log_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%log}}', [
            'id' => $this->primaryKey(),
            'flow' => $this->string(255)->notNull(),
            'workDate' => $this->date()->notNull(),
            'userId' => $this->integer()->notNull(),
            'workId' => $this->bigInteger()->notNull(),
            'eventiId' => $this->string(4)->notNull(),
            'powerAIResult' => $this->integer(),
            'powerAIInTime' => $this->string(12),
            'powerAIOutTime' => $this->string(12),
            'powerAICertainty' => $this->double(),
            'workTypeId' => $this->integer(),
            'status' => $this->smallInteger(),
            'time' => $this->string(12)->notNull(),
            'datetime' => $this->timestamp()->notNull(),
            'ObjectRecognitionResult' => $this->string(255),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%log}}');
    }
}
