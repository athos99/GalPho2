<?php

namespace app\controllers\admin;


use Yii;
use yii\web\Controller;
use app\models\GalGroup;

class GroupController extends Controller
{
    /**
     * Display a group list grid
     *
     * @return html view
     */
    public function actionIndex()
    {
        $model = new GalGroup(['scenario' => 'search']);
        $dataProvider = $model->search($_GET);

        return $this->render('//admin/group/list', [
            'dataProvider' => $dataProvider,
            'model' => $model]);
    }

    /*
     * Create a new group
     *
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new GalGroup;

        if ($model->load($_POST)) {
            $model->permanent = 0;
            if ($model->save()) {
                return $this->redirect(['admin/group']);
            }
        }
        return $this->render('//admin/group/create', [
            'model' => $model
        ]);
    }
}