<?php

namespace common\models;

use noam148\imagemanager\models\ImageManager;
use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "room".
 *
 * @property integer $id
 * @property string $name
 * @property integer $access_level
 * @property string $access token
 * @property integer $image_id
 *
 * @property Message[] $messages
 * @property WithAccessRoom[] $withAccessRooms
 * @property User[] $users
 */
class Room_old extends ActiveRecord
{
	public $access_with_token;

	public $access_token;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'room';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'image_id'], 'required'],
            [['access_level', 'image_id'], 'integer'],
            [['name'/*, 'access_token'*/], 'string', 'max' => 64],
	        [['access_with_token'], 'integer', 'min' => 0, 'max' => 1],
	        [['image_id'], 'exist', 'skipOnError' => true, 'targetClass' => Imagemanager::className(), 'targetAttribute' => ['image_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'access_level' => 'Access Level',
//            'access_token' => 'Access Token',
            'image_id' => 'Image ID',
	        'access_with_token' => 'Access With Token Only',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMessages()
    {
        return $this->hasMany(Message::className(), ['room_id' => 'id']);
    }

	public function beforeSave($insert)
	{
		if (parent::beforeSave($insert)) {

			if (Yii::$app->user->isGuest) {
				$this->setAttribute('access_level', 0);
				$this->setAttribute('access_token', NULL);
			} elseif (Yii::$app->request->post('Room')['access_with_token']) {
				$this->setAttribute('access_level', 1);
				$this->setAttribute('access_token', md5(openssl_random_pseudo_bytes(16)));
			}

			return true;
		} else {
			return false;
		}
	}

    /**
     * @return \yii\db\ActiveQuery
     */
	public function getImage()
	{
		return $this->hasOne(Imagemanager::className(), ['id' => 'image_id']);
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getWithAccessRooms()
	{
		return $this->hasMany(WithAccessRoom::className(), ['room_id' => 'id']);
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getUsers()
	{
		return $this->hasMany(User::className(), ['id' => 'user_id'])->viaTable('with_access_room', ['room_id' => 'id']);
	}

	public function getImagePath() {
		$imagePath = \Yii::$app->imagemanager->getImagePath($this->image_id, 100, 100, 'inset');

		return $imagePath;
	}
}
