<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "g2_gal_dir".
 *
 * @property integer $id
 * @property integer $element_id_cover
 * @property string $path
 * @property string $title
 * @property string $description
 * @property string $created_at
 * @property string $updated_at
 * @property string $sort_order
 *
 * @property GalElement[] $galElements
 * @property GalRight[] $galRights
 * @property GalGroup[] $groups
 */
class GalDirBase extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%gal_dir}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['element_id_cover'], 'integer'],
            [['path', 'description'], 'string'],
            [['created_at', 'updated_at'], 'safe'],
            [['title'], 'string', 'max' => 256],
            [['sort_order'], 'string', 'max' => 30]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'element_id_cover' => Yii::t('app', 'Element Id Cover'),
            'path' => Yii::t('app', 'Path'),
            'title' => Yii::t('app', 'Title'),
            'description' => Yii::t('app', 'Description'),
            'created_at' => Yii::t('app', 'Created at'),
            'updated_at' => Yii::t('app', 'Updated at'),
            'sort_order' => Yii::t('app', 'Sort Order'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getGalElements()
    {
        return $this->hasMany(GalElement::className(), ['dir_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getGalRights()
    {
        return $this->hasMany(GalRight::className(), ['dir_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getGroups()
    {
        return $this->hasMany(GalGroup::className(), ['id' => 'group_id'])->viaTable('g2_gal_right', ['dir_id' => 'id']);
    }
}
