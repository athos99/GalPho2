<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "gal_group_user".
 *
 * @property integer $group_id
 * @property integer $user_id
 *
 * @property TblUser $user
 * @property GalGroup $group
 */
class GalGroupUserBase extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%gal_group_user}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['group_id', 'user_id'], 'integer']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'group_id' => 'Group ID',
            'user_id' => 'User ID',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(TblUser::className(), ['id' => 'user_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getGroup()
    {
        return $this->hasOne(GalGroup::className(), ['id' => 'group_id']);
    }
}
