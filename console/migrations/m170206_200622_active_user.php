<?php

use yii\db\Migration;

class m170206_200622_active_user extends Migration
{

	public function up()
	{
		$this->createTable('active_user', [
			'ip' => 'varchar(32) NOT NULL',
			'last_query_time' => 'int(11) NOT NULL',
			'room_id' => 'int(11) unsigned NOT NULL',
			'PRIMARY KEY (`ip`,`room_id`)'
		]);

		$this->addForeignKey('active_user_ibfk_1', 'active_user', 'room_id', 'room', 'id', 'CASCADE');
	}

	public function down()
    {
        echo "m170206_200622_active_user cannot be reverted.\n";

        return false;
    }

	/*
	// Use safeUp/safeDown to run migration code within a transaction
	public function safeUp()
	{
	}

	public function safeDown()
	{
	}
	*/

}
