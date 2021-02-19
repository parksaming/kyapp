<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%alert_recommend}}`.
 */
class m191120_083250_create_alert_recommend_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%alert_recommend}}', [
            'id' => $this->primaryKey(),
            'workTypeId' => $this->integer()->notNull(),
            'organizationId' => $this->integer()->notNull(),
            'flowType' => $this->integer()->notNull(),
            'recommend' => $this->string(300)->notNull(),
            'createdAt' => $this->timestamp(),
            'updatedAt' => $this->timestamp(),
            'required' => $this->smallInteger(),

        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%alert_recommend}}');
    }
}
