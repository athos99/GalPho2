<?php

namespace app\controllers\admin;

use app\galpho\Galpho;
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
            /** @var \app\galpho\Galpho $galpho */
            $galpho = Yii::$app->get('galpho');
            $galpho->setIdPath($id);
            $galpho->getPathStructure();



            return $this->goBack();
//            $model->attributes = \Yii::$app->request->post('GalDir');

        }
        return $this->render('//admin/folder/create', ['model'=>$model]);
    }

}


