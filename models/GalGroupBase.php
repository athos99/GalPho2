<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "gal_group".
 *
 * @property integer $id
 * @property integer $permanent
 * @property string $name
 * @property string $description
 *
 * @property GalGroupUser[] $galGroupUsers
 * @property GalRight[] $galRights
 */
class GalGroupBase extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%gal_group}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [

        ];
    }
    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'permanent' => 'Permanent',
            'name' => 'Name',
            'description' => 'Description',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getGalGroupUsers()
    {
        return $this->hasMany(GalGroupUser::className(), ['group_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getGalRights()
    {
        return $this->hasMany(GalRight::className(), ['group_id' => 'id']);
    }
}
