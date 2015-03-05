<?php

namespace tests\fixtures;

use Yii;

/**
 * This is the model class for table "{{%gal_dir_lang}}".
 *
 * @property integer $id
 * @property integer $dir_id
 * @property string $language
 * @property string $title
 * @property string $description
 *
 * @property GalDir $dir
 */
class GalDirLangBase extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%gal_dir_lang}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['dir_id', 'language'], 'required'],
            [['dir_id'], 'integer'],
            [['description'], 'string'],
            [['language'], 'string', 'max' => 6],
            [['title'], 'string', 'max' => 256]
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
            'language' => 'Language',
            'title' => 'Title',
            'description' => 'Description',
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
