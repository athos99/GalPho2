<?php

namespace app\controllers\admin;

use Yii;
use yii\web\Controller;

class XeditController extends Controller
{
    public function actionIndex()
    {
        $model = Yii::$app->request->post('model', false);
        $pk = Yii::$app->request->post('pk', false);
        $value = Yii::$app->request->post('value', false);
        $name = Yii::$app->request->post('name', false);

        if ($pk !== false && $value != false && !empty($model) && !empty($name)) {
            $class = 'app\\models\\' . $model;
            if (class_exists($class) && is_subclass_of($class, '\yii\db\ActiveRecord')) {
                $ar = $class::findOne($pk);
                if (($ar !== null) ) {
                    $ar->$name = $value;
                    $ar->save();
                    exit();
                }
            }
        }

        $response = Yii::$app->response;
        $response->statusCode = 400;
        $response->content = Yii::t('app/admin', 'Error, the value can\'t be change');
    }
}


