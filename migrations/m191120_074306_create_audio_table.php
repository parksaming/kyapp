<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%audio}}`.
 */
class m191120_074306_create_audio_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%audio}}', [
            'id' => $this->primaryKey(),
            'checkType' => $this->integer()->notNull(),
            'taskId' => $this->bigInteger()->notNull(),
            'fileCode' => $this->string(255)->notNull(),
            'fileName' => $this->string(255)->notNull(),
            'createdAt' => $this->timestamp(),
            'updatedAt' => $this->timestamp()
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%audio}}');
    }
}
