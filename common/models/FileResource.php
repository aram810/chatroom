<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "file_resource".
 *
 * @property integer $id
 * @property string $path
 * @property string $name
 * @property string $extension
 *
 * @property Room[] $rooms
 */
class FileResource extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'file_resource';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['path', 'name', 'extension'], 'required'],
            [['path'], 'string', 'max' => 512],
            [['name'], 'string', 'max' => 128],
            [['extension'], 'string', 'max' => 32],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'path' => 'Path',
            'name' => 'Name',
            'extension' => 'Extension',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRooms()
    {
        return $this->hasMany(Room::className(), ['image_id' => 'id']);
    }
}
