<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\User */
/* @var $form ActiveForm */
$this->title = Yii::t('app/admin', 'Admin create user');
$this->params['breadcrumbs'][] = $this->title;
?>
<h1><?= $this->title ?></h1>
<div class="folder-form">
    <?php $form = ActiveForm::begin(); ?>
    <?= $form->field($model, 'username') ?>
    <?= $form->field($model, 'email') ?>
    <div class="form-group">
        <?= Html::resetButton(Yii::t('app/admin', 'Reset'), ['class' => 'btn btn-default']); ?>
        <?= Html::submitButton(Yii::t('app/admin', 'Cancel'), ['class' => 'btn btn-default  no-validation', 'name' => 'cancel']) ?>
        <?= Html::submitButton(Yii::t('app/admin', 'Create'), ['class' => 'btn btn-primary', 'name' => 'create']) ?>
    </div>
    <?php ActiveForm::end(); ?>
</div>
