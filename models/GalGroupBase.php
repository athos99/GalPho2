<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "g2_gal_group".
 *
 * @property integer $id
 * @property integer $permanent
 * @property string $name
 * @property string $description
 *
 * @property GalGroupUser[] $galGroupUsers
 * @property GalRight[] $galRights
 * @property GalDir[] $dirs
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
            [['permanent'], 'integer'],
            [['description'], 'string'],
            [['name'], 'string', 'max' => 128]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'permanent' => Yii::t('app', 'Permanent'),
            'name' => Yii::t('app', 'Name'),
            'description' => Yii::t('app', 'Description'),
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

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDirs()
    {
        return $this->hasMany(GalDir::className(), ['id' => 'dir_id'])->viaTable('g2_gal_right', ['group_id' => 'id']);
    }
}
