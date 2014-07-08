<?php

namespace app\controllers\admin;

use Yii;
use yii\web\Controller;
use app\models\GalDir;

class FolderController extends Controller
{

    public function actionAdd($id)
    {
        return $this->render('//admin/test');
    }
    public function actionCreate($id)
    {
        $model = new GalDir(['scenario'=>'form']);
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            return $this->goBack();
//            $model->attributes = \Yii::$app->request->post('GalDir');

        }
        return $this->render('//admin/folder/create', ['model'=>$model]);
    }

}


