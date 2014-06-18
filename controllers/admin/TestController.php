<?php

namespace app\controllers\admin;

use Yii;
use yii\web\Controller;

class TestController extends Controller
{

    public function actionIndex()
    {
        return $this->render('//admin/test');
    }
}


