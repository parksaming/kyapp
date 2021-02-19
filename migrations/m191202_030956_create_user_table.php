<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%user}}`.
 */
class m191202_030956_create_user_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%user}}', [
            'id' => $this->primaryKey(),
            'organizationId' => $this->integer()->notNull(),
            'workChargeId' => $this->integer(),
            'roleId' =>  $this->integer()->notNull(),
            'userCode' =>  $this->string(45)->notNull(),
            'userName' =>  $this->string(255)->notNull(),
            'email' =>  $this->string(45)->notNull(),
            'password' =>  $this->string(100),
            'phone' =>  $this->string(45)->notNull(),
            'attemptCount' =>  $this->integer(),
            'isLocked' =>  $this->smallInteger(),
            'token' =>  $this->string(45),
            'tokenCreateAt' =>  $this->timestamp(),
            'isDeleted' =>  $this->smallInteger(),
            'createdAt' => $this->timestamp(),
            'updatedAt' => $this->timestamp(),
            'companyCode' =>  $this->string(45),
            'imei' =>  $this->string(15),
            'supportChargeId' =>  $this->string(45),
            'supportStartAt' =>  $this->string(10),
            'supportEndAt' =>  $this->date(),
            'kyotenType' =>  $this->integer()->notNull(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%user}}');
    }
}
