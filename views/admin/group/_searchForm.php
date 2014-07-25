<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/**
 * @var yii\base\View $this
 * @var app\models\GalGrouph $model
 * @var yii\widgets\ActiveForm $form
 */
?>

<div class="gal-group-search">

    <?php $form = ActiveForm::begin(array(
        'action' => ['admin/group'],
        'method' => 'get',
    )); ?>

    <?php echo $form->field($model, 'id'); ?>
    <?php echo $form->field($model, 'name'); ?>
    <?php echo $form->field($model, 'description'); ?>
    <div class="form-group">
        <?php echo Html::submitButton('Search', array('class' => 'btn btn-primary')); ?>
        <?php echo Html::resetButton('Reset', array('class' => 'btn btn-default')); ?>
    </div>
    <?php ActiveForm::end(); ?>
</div>
