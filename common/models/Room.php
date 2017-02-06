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
 * @property integer $image_id
 * @property string $token
 * @property integer $created_by
 *
 * @property Message[] $messages
 * @property Imagemanager $image
 * @property User $createdBy
 */
class Room extends ActiveRecord
{
	public $access_with_token;

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
            [['access_level', 'image_id', 'created_by'], 'integer'],
            [['name'], 'string', 'max' => 64],
            [['token'], 'string', 'max' => 32],
            [['image_id'], 'exist', 'skipOnError' => true, 'targetClass' => Imagemanager::className(), 'targetAttribute' => ['image_id' => 'id']],
            [['created_by'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['created_by' => 'id']],
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

	/**
	 * @param bool $insert
	 *
	 * @return bool
	 */
	public function beforeSave($insert)
	{
		if (parent::beforeSave($insert)) {

			if (Yii::$app->user->isGuest) {
				$this->setAttribute('access_level', 0);
				$this->setAttribute('token', null);
			} elseif (Yii::$app->request->post('Room')['access_with_token']) {
				$this->setAttribute('access_level', 1);
				$this->setAttribute('token', md5(openssl_random_pseudo_bytes(16)));
				$this->setAttribute('created_by', Yii::$app->user->id);
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
    public function getCreatedBy()
    {
        return $this->hasOne(User::className(), ['id' => 'created_by']);
    }

	public function getImagePath() {
		$imagePath = \Yii::$app->imagemanager->getImagePath($this->image_id, 100, 100, 'inset');

		return $imagePath;
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getActiveUsers()
	{
		return $this->hasMany(ActiveUser::className(), ['room_id' => 'id']);
	}

	public function getActiveUsersCount()
	{
		$ip = md5(Yii::$app->request->getUserIP());
		$now = time();

		$exist = ActiveUser::find()->where(['ip' => $ip, 'room_id' => $this->id])->exists();

		if ($exist) {
			$activeUser = ActiveUser::find()->where(['ip' => $ip, 'room_id' => $this->id])->one();

			$activeUser->setAttribute('ip', $ip);
			$activeUser->setAttribute('room_id', $this->id);
			$activeUser->setAttribute('last_query_time', $now);

			$activeUser->save();
		} else {
			$activeUser = new ActiveUser();

			$activeUser->setAttribute('ip', $ip);
			$activeUser->setAttribute('last_query_time', $now);
			$activeUser->setAttribute('room_id', $this->id);

			$activeUser->save();
		}

		$activeUsersCount = ActiveUser::find()
			->where(['and', ['>=', 'last_query_time', $now - 3], ['room_id' => $this->id]])
			->count();

		ActiveUser::deleteAll(['<', 'last_query_time', $now - 3]);

		return $activeUsersCount;
	}
}
