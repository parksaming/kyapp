<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%image}}`.
 */
class m191120_074325_create_image_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%image}}', [
            'id' => $this->primaryKey(),
            'checkType' => $this->integer()->notNull(),
            'taskId' => $this->bigInteger()->notNull(),
            'checkItemId' => $this->integer(),
            'fileCode' => $this->string(255)->notNull(),
            'fileName' => $this->string(255)->notNull(),
            'objectRecognizeResult' => $this->smallInteger(),
            'isConfirmed' => $this->smallInteger(),
            'createdAt' => $this->timestamp(),
            'updatedAt' => $this->timestamp()
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%image}}');
    }
}
