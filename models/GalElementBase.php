<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "g2_gal_element".
 *
 * @property integer $id
 * @property integer $dir_id
 * @property string $name
 * @property string $title
 * @property string $description
 * @property string $info
 * @property string $created_at
 * @property string $updated_at
 * @property string $format
 * @property integer $rank
 *
 * @property GalDir $dir
 */
class GalElementBase extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%gal_element}}';
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
            [['created_at', 'updated_at'], 'safe'],
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
            'id' => Yii::t('app', 'ID'),
            'dir_id' => Yii::t('app', 'Dir ID'),
            'name' => Yii::t('app', 'Name'),
            'title' => Yii::t('app', 'Title'),
            'description' => Yii::t('app', 'Description'),
            'info' => Yii::t('app', 'Info'),
            'created_at' => Yii::t('app', 'Created at'),
            'updated_at' => Yii::t('app', 'Updated at'),
            'format' => Yii::t('app', 'Format'),
            'rank' => Yii::t('app', 'Rank'),
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
