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
        $galpho->clearCache();
        Yii::$app->getSession()->setFlash('info', 'cache cleared');
        return Yii::$app->getResponse()->redirect($galpho->url);
    }
}


