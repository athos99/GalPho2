<?php

namespace app\controllers\admin;

use Yii;
use yii\web\Controller;
use yii\helpers\FileHelper;
use app\models\DbTableDependency;

class CacheController extends Controller
{

    public function actionClear()
    {
        /** @var \app\galpho\Galpho $galpho */
        $galpho = Yii::$app->get('galpho');
        DbTableDependency::reset();
        FileHelper::removeDirectory(Yii::getAlias('@app/') . Yii::$app->params['image']['cache']);
        $dir = Yii::getAlias('@app/') . Yii::$app->params['image']['src'];
        if ( !is_dir($dir)) {
            mkdir( $dir,777,true);
        }

        $list = FileHelper::findFiles(Yii::getAlias('@app/') . Yii::$app->params['image']['src'], ['only' => ['right.php']]);
        foreach( $list as  $file) {
            unlink($file);
        }
        Yii::$app->getSession()->setFlash('info', 'cache cleared');
        return Yii::$app->getResponse()->redirect($galpho->url);
    }


}


