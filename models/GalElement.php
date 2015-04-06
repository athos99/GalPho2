<?php
namespace app\models;
use yii;
use app\galpho\MultiLingualTrait;


/**
 * This is the model class for table "g2_gal_element".
 *
 * @property GalDir $dirLocalizedAll
 */


class GalElement extends GalElementBase
{
    use MultiLingualTrait;

    public static $langForeignKey = 'element_id';
    public static $langLanguages = [];
    public static $langAttributes = ['title', 'description'];



    public function behaviors()
    {
        return [
            'activeRecordDependency' => [
                'class' => 'app\models\ActiveRecordDependency',
            ],
            'timestamp' => [
                'class' => 'yii\behaviors\TimestampBehavior',
                'attributes' => [
                    yii\db\ActiveRecord::EVENT_BEFORE_INSERT => ['created_at', 'updated_at'],
                    yii\db\ActiveRecord::EVENT_BEFORE_UPDATE => ['updated_at'],

                ],
                'value' => new yii\db\Expression('NOW()'),
            ],
        ];
    }


    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDirLocalizedAll($lang)
    {
        return $this->hasOne(MultiDir::className(), ['id' => 'dir_id'])->localized('all');
    }

}
