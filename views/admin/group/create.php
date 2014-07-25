<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\GalGroup */
/* @var $form ActiveForm */

?>
<h1>admin group create</h1>
<div class="folder-form">
    <?php $form = ActiveForm::begin(); ?>
    <?= $form->field($model, \Yii::t('app/admin', 'name')) ?>
    <?= $form->field($model, \Yii::t('app/admin', 'description')) ?>
    <div class="form-group">
        <?= Html::submitButton(\Yii::t('app/admin', 'Create group'), ['class' => 'btn btn-primary']) ?>
    </div>
    <?php ActiveForm::end(); ?>
</div>
