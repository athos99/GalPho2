<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/*
 * @var $this yii\web\View
 * @var $galdir app\models\GalDir
 * @var $form ActiveForm
 * @var app\galpho\Galpho $galpho
 */

?>
<h1>admin folder create</h1>
<div class="folder-form">
    <?php $form = ActiveForm::begin(); ?>
    <?= $form->field($model, 'title') ?>
    <?= $form->field($model, 'description')->textarea() ?>
    <?= $form->field($model, 'url', ['enableAjaxValidation' => true]) ?>
    <?= $form->field($model, 'auto_path')->checkbox(['label'=>'Url auto']) ?>

    <div class="form-group">
        <?= Html::resetButton(Yii::t('app/admin', 'Reset'), ['class' => 'btn btn-default']); ?>
        <?= Html::submitButton(Yii::t('app/admin', 'Cancel'), ['class' => 'btn btn-default no-validation', 'name' => 'cancel']) ?>
        <?= Html::submitButton(Yii::t('app/admin','Create folder'), ['class' => 'btn btn-primary']) ?>
    </div>
    <?php ActiveForm::end(); ?>

</div><!-- folder-form -->

