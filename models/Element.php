<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "gal_element".
 *
 * @property integer $id
 * @property integer $dir_id
 * @property string $name
 * @property string $title
 * @property string $description
 * @property string $create_time
 * @property string $update_time
 * @property string $format
 * @property integer $rank
 *
 * @property GalDir $dir
 */
class Element extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'gal_element';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['dir_id', 'format'], 'required'],
            [['dir_id', 'rank'], 'integer'],
            [['description'], 'string'],
            [['create_time', 'update_time'], 'safe'],
            [['name'], 'string', 'max' => 128],
            [['title'], 'string', 'max' => 256],
            [['format'], 'string', 'max' => 10]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'dir_id' => 'Dir ID',
            'name' => 'Name',
            'title' => 'Title',
            'description' => 'Description',
            'create_time' => 'Create Time',
            'update_time' => 'Update Time',
            'format' => 'Format',
            'rank' => 'Rank',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDir()
    {
        return $this->hasOne(GalDir::className(), ['id' => 'dir_id']);
    }
}
