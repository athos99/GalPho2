<?php

namespace app\controllers\admin;


use Yii;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use app\models\User;

class UserController extends Controller
{
    /**
     * Display a group list grid
     *
     * @return html view
     */
    public function actionIndex()
    {
        $model = new User(['scenario' => 'search']);
        $dataProvider = $model->query(ArrayHelper::getValue($_GET, 'search', ''));

        return $this->render('//admin/user/list', [
            'dataProvider' => $dataProvider]);
    }

    /*
     * Create a new user
     *
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new User;

        if ( array_key_exists('create', $_POST) && $model->load($_POST)) {
            $model->permanent = 0;
            if ($model->save()) {
                return $this->redirect(['admin/user']);
            }
        } elseif (array_key_exists('cancel', $_POST)) {
            return $this->redirect(['admin/user']);

        }
        return $this->render('//admin/user/create', [
            'model' => $model
        ]);
    }

    /**
     *  Edit user
     *
     * @param $id
     */
    public function actionUpdate($id)
    {
        $model = User::findOne($id);

        if (  array_key_exists('save', $_POST) && $model->load($_POST)) {
            if ($model->save()) {
                return $this->redirect(['admin/user']);
            }
        } elseif (array_key_exists('cancel', $_POST)) {
            return $this->redirect(['admin/user']);
        }
        return $this->render('//admin/user/edit', [
            'model' => $model
        ]);
    }

    /**
     *  delete user
     *
     * @param $id
     */
    public function actionDelete($id)
    {
        $model = User::findOne($id);
        if ($model !== null && !$model->permanent) {
            $model->delete();
        }
        return $this->redirect(['admin/user']);
    }
}