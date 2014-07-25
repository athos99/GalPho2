<?php

namespace app\controllers\admin;


use Yii;
use yii\web\Controller;
use app\models\GalGroup;

class GroupController extends Controller
{

    public function actionIndex() {
        $model = new GalGroup;
        $dataProvider = $model->search($_GET);

        return $this->render('//admin/group/list', [
            'dataProvider' => $dataProvider,
            'model' => $model,
        ]);
    }

    public function actionAdd($id)
    {
        return $this->render('//admin/group');
    }

    public function actionCreate($id)
    {
        $model = new GalDir(['scenario' => 'form']);
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            /** @var \app\galpho\Galpho $galpho */
            $galpho = Yii::$app->get('galpho');
            $galpho->setIdPath($id);
            $dir = new GalDir();
            $name = BaseInflector::slug(trim($model->title), '-', true);
            $dir->title = trim($model->title);
            $dir->path = trim($galpho->getPath() . '/' . $name, '/');
            $dir->description = trim($model->description);
            if ($dir->save()) {
                $right = new GalRight();
                $right->group_id = 1;
                $right->dir_id = $dir->id;
                $right->value = 0x07;
                $right->save();
            }
            return Yii::$app->getResponse()->redirect($galpho->url . '/' . $dir->path);
        }
        return $this->render('//admin/folder/create', ['model' => $model]);
    }

}