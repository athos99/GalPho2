<?php

namespace app\controllers\admin;


use Yii;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use app\models\GalGroup;
use app\models\User;

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
        $dataProvider = $model->query(ArrayHelper::getValue($_GET, 'search', ''));
        return $this->render('//admin/group/list', [
            'dataProvider' => $dataProvider]);
    }

    /*
     * Create a new group
     *
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new GalGroup;

        if (array_key_exists('create', $_POST) && $model->load($_POST)) {
            $model->permanent = 0;
            if ($model->save()) {
                return $this->redirect(['admin/group']);
            }
        } elseif (array_key_exists('cancel', $_POST)) {
            return $this->redirect(['admin/group']);

        }
        return $this->render('//admin/group/create', [
            'model' => $model
        ]);
    }

    /**
     *  Edit group
     *
     * @param $id
     */
    public function actionUpdate($id)
    {
        $model = galGroup::findOne($id);

        if (array_key_exists('save', $_POST) && $model->load($_POST)) {
            if ($model->save()) {
                return $this->redirect(['admin/group']);
            }
        } elseif (array_key_exists('cancel', $_POST)) {
            return $this->redirect(['admin/group']);
        }
        return $this->render('//admin/group/edit', [
            'model' => $model
        ]);
    }

    /**
     *  Edit users of group
     *
     * @param $id
     */
    public function actionUser($id)
    {
        $group = galGroup::findOne($id);
        $selUsers = $group->galGroupUsers;
        $users = User::find()->orderBy('username')->all();


        if ( array_key_exists('save', $_POST)) {
            $group->saveUser( ArrayHelper::getValue($_POST,'galGroupUsers',[]));
            if ($group->save()) {
                return $this->redirect(['admin/group']);
            }
        } elseif (array_key_exists('cancel', $_POST)) {
            return $this->redirect(['admin/group']);

        }
        return $this->render('//admin/group/user', [
            'group' => $group,
            'users' => $users,
            'selUsers' => $selUsers
        ]);
    }


    /**
     *  delete group
     *
     * @param $id
     */
    public function actionDelete($id)
    {
        $model = galGroup::findOne($id);
        if ($model !== null && !$model->permanent) {
            $model->delete();
        }
        return $this->redirect(['admin/group']);
    }
}