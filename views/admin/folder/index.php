<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use app\models\GalDir;
use app\galpho;
use \yii\bootstrap\Tabs;

/*
 * @var $this yii\web\View
 * @var $model app\models\GalDir
 * @var $form ActiveForm
 * @var app\galpho\Galpho $galpho
 */

echo Tabs::widget([
    'items' => [
        [
            'label' => 'One',
            'content' => 'Anim pariatur cliche...',
            'active' => true
        ],
        [
            'label' => 'Two',
            'content' => 'Anim pariatur cliche...',
        ],
    ]
]);



echo $this->render('//admin/folder/edit',  ['model' => $model, 'galpho' => $galpho]);
echo $this->render('//admin/folder/right', [
    'records' => $records,
    'galGroup' => $galGroup

]);
