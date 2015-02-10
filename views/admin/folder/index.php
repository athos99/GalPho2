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

$this->title = Yii::t('app/admin', 'Edit folder {folder}', ['folder' => ArrayHelper::getValue($model->title, GalDir::defaultLanguage())]);
$this->params['breadcrumbs'][] = $this->title;



?><h1 class="page-title"><?= Html::encode($this->title) ?></h1>
<div class="folder-form page-content">
<?php $form = ActiveForm::begin(); ?>
<?php


echo Tabs::widget([
    'items' => [
        [
            'label' => 'One',
            'active' => true,
            'content' => $this->render('//admin/folder/edit', [
                    'model' => $model,
                    'galpho' => $galpho,
                    'form' => $form
                ])
        ],
        [
            'label' => 'Two',
            'content' => $this->render('//admin/folder/right', [
                    'records' => $records,
                    'galGroup' => $galGroup,
                    'form' => $form
                ]),
        ],
    ]
]);
?>

    <div class="row">
        <div class="form-group">
            <?= Html::resetButton(Yii::t('app/admin', 'Reset'), ['class' => 'btn btn-default']); ?>
            <?= Html::submitButton(Yii::t('app/admin', 'Cancel'), ['class' => 'btn btn-default  no-validation dialog-close', 'name' => 'cancel']) ?>
            <?= Html::submitButton(Yii::t('app/admin', 'Save'), ['class' => 'btn btn-primary', 'name' => 'save']) ?>
        </div>
    </div>

<?php ActiveForm::end();