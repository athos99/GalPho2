<?php

namespace app\controllers\admin;

use Yii;

class TestController extends BaseController
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


