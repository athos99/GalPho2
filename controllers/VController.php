<?php
namespace app\controllers;

use yii\helpers\ArrayHelper;
use yii\web\Controller;


class VController extends Controller
{
    public function actionIndex()
    {
        $path = ArrayHelper::getValue($_GET, 'path', '/');

        /** @var app\galpho\Galpho $galpho */
        $galpho = \yii::$app->get('galpho');
        $galpho->setPath($path);
        $galpho->setGroups(array(1, 2, 3));
//        $galpho->repair();
        switch ($galpho->getViewMode()) {
            case app\galpho\galpho::VIEW_LIST :
                echo $this->render('//site/list', array('galpho' => $galpho));
                break;
            case app\galpho::VIEW_DETAIL :
                echo $this->render('//site/detail', array('galpho' => $galpho));
                break;
            default :
                Yii::$app->getResponse()->redirect(array('/'));
        }

    }
}
