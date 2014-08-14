<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\GalGroup */
/* @var $form ActiveForm */

$this->title = Yii::t('app/admin', 'Admin edit group');
$this->params['breadcrumbs'][] = $this->title;
?>
<h1><?= $this->title ?></h1>
<div class="folder-form">
    <?php $form = ActiveForm::begin(); ?>
    <?= $form->field($model, 'name') ?>
    <?= $form->field($model, 'description') ?>
    <div class="form-group">
        <?= Html::resetButton(\Yii::t('app/admin', 'Reset'), ['class' => 'btn btn-default']); ?>
        <?= Html::submitButton(\Yii::t('app/admin', 'Cancel'), ['class' => 'btn btn-default', 'name' => 'cancel']) ?>
        <?= Html::submitButton(\Yii::t('app/admin', 'Save'), ['class' => 'btn btn-primary', 'name' => 'save']) ?>
    </div>
    <?php ActiveForm::end(); ?>
</div>
