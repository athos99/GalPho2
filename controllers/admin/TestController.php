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

    public function actionGenerate()
    {

        $adminTool =new \app\galpho\AdminTool();
        $adminTool->generateGallery();
        return $this->render('//admin/test/generate');


    }

}


