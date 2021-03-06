<?php

namespace app\controllers\admin;


use Yii;
use yii\helpers\ArrayHelper;
use yii\helpers\BaseInflector;
use app\models\GalRight;
use app\models\GalDir;
use app\models\GalGroup;
use yii\web\Response;
use yii\widgets\ActiveForm;

class FolderController extends BaseController
{


    public function actionCreate($id)
    {
        /** @var \app\galpho\Galpho $galpho */
        $galpho = Yii::$app->get('galpho');
        $model = new GalDir(['scenario' => 'form']);
        $model::$langLanguages = $galpho->getLanguages();
        $model->language = 'fr';
        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $galpho->setIdPath($id);
            $dir = new GalDir();
            $name = BaseInflector::slug(trim($model->title), '-', true);
            $dir->title = trim($model->title);
            $dir->path = rtrim($galpho->getPath() . '/' . $name, '/');
            $dir->description = trim($model->description);
            if ($dir->save()) {
                $right = new GalRight();
                $right->group_id = 1;
                $right->dir_id = $dir->id;
                $right->value = 0x07;
                $right->save();
            }
            return Yii::$app->getResponse()->redirect($galpho->url . $dir->path);
        }
        $model->auto_path = 1;
        return $this->render('//admin/folder/create', ['model' => $model, 'galpho' => $galpho]);
    }


    public function actionIndex($id)
    {

        /** @var \app\galpho\Galpho $galpho */
        $galpho = Yii::$app->get('galpho');
        $galpho->setIdPath($id);
        /** @var GalDir $model */
        $model = GalDir::find()
            ->with('galElementsLocalizedAll')
            ->where(['id' => $id])->localized('all')->one();
        $galElements = $model->galElementsLocalizedAll;


        $model->setScenario('form');
        $model::$langLanguages = $galpho->getLanguages();

        if (isset(Yii::$app->request->post()['cancel'])) {
            return Yii::$app->getResponse()->redirect($galpho->url . $galpho->path);
        }
        if (array_key_exists('save', $_POST)) {

            if ($model->load(Yii::$app->request->post()) && $model->validate()) {
                $model->save();


                $galpho->renameFolder($model->url);
                $galDir = GalDir::findOne($id);
                $rawRights = ArrayHelper::getValue($_POST, 'r', []);
                $rights = [];
                foreach ($rawRights as $rightId => $right) {
                    $value = 0;
                    foreach ($right as $v) {
                        $value += $v;
                    }
                    $rights[$rightId] = $value;
                }
                $galDir->saveRight($rights, !empty($_POST['children']));
                return Yii::$app->getResponse()->redirect($galpho->url . $galpho->path);
            }
        }
        $galGroup = new GalGroup();
        $records = GalGroup::find()
            ->with(['galRights' => function ($query) use ($id) {
                    $query->andWhere(['dir_id' => $id]);
                }])
            ->orderBy('name')
            ->all();

        return $this->render('//admin/folder/index', [
            'model' => $model,
            'galElements'=>&$galElements,
            'galpho' => $galpho,
            'records' => $records,
            'galGroup' => $galGroup
        ]);
    }

    public function actionElement($id)
    {

        /** @var \app\galpho\Galpho $galpho */
        $galpho = Yii::$app->get('galpho');
        $galpho->setIdPath($id);
        $model = GalDir::find()->where(['id' => $id])->localized('all')->one();
//        $model = GalDir::findOne($id);
        $model->setScenario('form');
        $model::$langLanguages = $galpho->getLanguages();

        if (isset(Yii::$app->request->post()['cancel'])) {
            return Yii::$app->getResponse()->redirect($galpho->url . $galpho->path);
        }
        if (array_key_exists('save', $_POST)) {

            if ($model->load(Yii::$app->request->post()) && $model->validate()) {
                $model->save();


                $galpho->renameFolder($model->url);
                $galDir = GalDir::findOne($id);
                $rawRights = ArrayHelper::getValue($_POST, 'r', []);
                $rights = [];
                foreach ($rawRights as $rightId => $right) {
                    $value = 0;
                    foreach ($right as $v) {
                        $value += $v;
                    }
                    $rights[$rightId] = $value;
                }
                $galDir->saveRight($rights, !empty($_POST['children']));
                return Yii::$app->getResponse()->redirect($galpho->url . $galpho->path);
            }
        }
        $galGroup = new GalGroup();
        $records = GalGroup::find()
            ->with(['galRights' => function ($query) use ($id) {
                    $query->andWhere(['dir_id' => $id]);
                }])
            ->orderBy('name')
            ->all();

        return $this->render('//admin/folder/index', [
            'model' => $model,
            'galpho' => $galpho,
            'records' => $records,
            'galGroup' => $galGroup
        ]);
    }

}


