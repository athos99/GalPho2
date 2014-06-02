<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "gal_right".
 *
 * @property integer $group_id
 * @property integer $dir_id
 * @property integer $value
 *
 * @property GalDir $dir
 * @property GalGroup $group
 */
class GalRight extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'gal_right';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['group_id', 'dir_id', 'value'], 'integer']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'group_id' => 'Group ID',
            'dir_id' => 'Dir ID',
            'value' => 'Value',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDir()
    {
        return $this->hasOne(GalDir::className(), ['id' => 'dir_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getGroup()
    {
        return $this->hasOne(GalGroup::className(), ['id' => 'group_id']);
    }
}
