<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use app\models\GalDir;
use app\galpho;


/*
 * @var $this yii\web\View
 * @var $model app\models\GalDir
 * @var $form ActiveForm
 * @var app\galpho\Galpho $galpho
 */




$this->title = Yii::t('app/admin', 'Edit folder {folder}', ['folder' =>ArrayHelper::getValue($model->title, GalDir::defaultLanguage())]);
$this->params['breadcrumbs'][] = $this->title;
?>
<h1><?= Html::encode($this->title) ?></h1>
<div class="folder-form">
    <?php $form = ActiveForm::begin(); ?>
    <?= $form->field($model, 'title')->widget(galpho\MultiLingualInput::className(), [
        'languages' => $galpho->getLanguages() ]);?>
    <?= $form->field($model, 'description')->widget(galpho\MultiLingualInput::className(), [
        'languages' => $galpho->getLanguages(), 'type'=>'textarea' ]);?>
    <?= $form->field($model, 'url') ?>
    <?= $form->field($model, 'auto_path')->checkbox() ?>

    <div class="form-group">
        <?= Html::resetButton(Yii::t('app/admin', 'Reset'), ['class' => 'btn btn-default']); ?>
        <?= Html::submitButton(Yii::t('app/admin', 'Cancel'), ['class' => 'btn btn-default no-validation', 'name' => 'cancel']) ?>
        <?= Html::submitButton(Yii::t('app/admin','Update'), ['class' => 'btn btn-primary']) ?>
    </div>
    <?php ActiveForm::end(); ?>

</div><!-- folder-form -->

