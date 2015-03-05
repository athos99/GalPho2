<?php
namespace app\models;
use yii;

class GalRight extends GalRightBase
{


    public function behaviors()
    {
        return [
            'activeRecordDependency' => [
                'class' => 'app\models\ActiveRecordDependency'
            ],
        ];
    }


    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);
        // get src image directory
        $dir = GalDir::findOne($this->dir_id);
        if ($dir->path == '') {
            $filename = Yii::getAlias('@app/' . Yii::$app->params['image']['src']) . '/right.php';
        } else {
            $filename = Yii::getAlias('@app/' . Yii::$app->params['image']['src']) . '/' . $dir->path . '/right.php';
        }
        @unlink($filename);
    }

}
