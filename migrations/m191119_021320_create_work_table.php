<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%work}}`.
 */
class m191119_021320_create_work_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%work}}', [
            'id' => $this->primaryKey(),
            'userCode' => $this->string(45)->notNull(),
            'workedOrganizationId' => $this->integer()->notNull(),
            'code' => $this->string(45)->notNull(),
            'officeComment' => $this->string(300),
            'status' => $this->smallInteger()->defaultValue(1),
            'start' => $this->timestamp()->defaultValue(null),
            'end' => $this->timestamp()->defaultValue(null),
            'checkType' => $this->integer(),
            'date' => $this->date()->notNull(),
            'powerAIImage' => $this->string(255),
            'confirmStatus' => $this->smallInteger(),
            'isObjectRecognizeSuccess' => $this->smallInteger(),
            'faOperationId' => $this->string(30),
            'faOperationKind' => $this->string(30),
            'faOperationDetail' => $this->string(100),
            'faOperationDate' => $this->string(30),
            'faConstructionTimePeriod' => $this->string(30),
            'createdAt' => $this->timestamp(),
            'updatedAt' => $this->timestamp()
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%work}}');
    }
}
