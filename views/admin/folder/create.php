<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\GalDir */
/* @var $form ActiveForm */

?>
<h1>admin folder create</h1>
<div class="folder-form">

    <?php $form = ActiveForm::begin(); ?>
    <?= $form->field($model, 'title') ?>
    <?= $form->field($model, 'description') ?>
    <div class="form-group">
        <?= Html::resetButton(\Yii::t('app/admin', 'Reset'), ['class' => 'btn btn-default']); ?>
        <?= Html::submitButton(\Yii::t('app/admin', 'Cancel'), ['class' => 'btn btn-default no-validation', 'name' => 'cancel']) ?>
        <?= Html::submitButton(Yii::t('app/admin','Create folder'), ['class' => 'btn btn-primary']) ?>
    </div>
    <?php ActiveForm::end(); ?>

</div><!-- folder-form -->

