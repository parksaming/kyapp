<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%confirm_type}}`.
 */
class m191216_102142_create_confirm_type_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%confirm_type}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string(100)->notNull(),
            'createdAt' => $this->timestamp(),
            'updatedAt' => $this->timestamp(),
            'isRecordAudio' => $this->smallInteger(),
            'aerialWork' => $this->smallInteger()->notNull(),
            'displayOrder' => $this->integer()->notNull(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%confirm_type}}');
    }
}
