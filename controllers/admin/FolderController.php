<?php

namespace app\controllers\admin;


use Yii;
use yii\data\ActiveDataProvider;
use yii\helpers\BaseInflector;
use yii\web\Controller;
use app\models\GalDir;
use app\models\GalRight;
use yii\web\Response;
use yii\widgets\ActiveForm;

class FolderController extends Controller
{


    public function actionCreate($id)
    {
        $model = new GalDir(['scenario' => 'form']);
        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }
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
        $model->auto_path = 1;
        return $this->render('//admin/folder/create', ['model' => $model]);
    }

    public function actionEdit($id)
    {
        $model = GalDir::findOne($id);
        $model->setScenario('form');
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            /** @var \app\galpho\Galpho $galpho */
            $galpho = Yii::$app->get('galpho');
            $galpho->setIdPath($id);
            $model->title = trim($model->title);
            $model->description = trim($model->description);
            $model->save();
            $galpho->renameFolder($model->url);
            return Yii::$app->getResponse()->redirect($galpho->url . '/' . $galpho->path);
        }
        return $this->render('//admin/folder/edit', ['model' => $model]);
    }


    public function actionRight($id)
    {
        $query = GalRight::find()
            ->where(['dir_id'=>$id])
            ->joinWith('group')
        //    ->orderBy('name')
        ;
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 20,
            ],
        ]);
        return $this->render('//admin/folder/right', [
            'dataProvider' => $dataProvider,
        ]);
    }


}


