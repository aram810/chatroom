<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "active_user".
 *
 * @property string $ip
 * @property integer $last_query_time
 * @property integer $room_id
 *
 * @property Room $room
 */
class ActiveUser extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'active_user';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['ip', 'last_query_time', 'room_id'], 'required'],
            [['last_query_time', 'room_id'], 'integer'],
            [['ip'], 'string', 'max' => 32],
            [['room_id'], 'exist', 'skipOnError' => true, 'targetClass' => Room::className(), 'targetAttribute' => ['room_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'ip' => 'Ip',
            'last_query_time' => 'Last Query Time',
            'room_id' => 'Room ID',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRoom()
    {
        return $this->hasOne(Room::className(), ['id' => 'room_id']);
    }
}
