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
    <?= $form->field($model, 'id'); ?>
    <?= $form->field($model, 'name'); ?>
    <?= $form->field($model, 'description'); ?>
    <div class="form-group">
        <?= Html::resetButton(\Yii::t('app/admin', 'Reset'), ['class' => 'btn btn-default']); ?>
        <?= Html::submitButton(\Yii::t('app/admin', 'Search'), ['class' => 'btn btn-primary','name'=>'cmd','value'=>'search']); ?>
    </div>
    <?php ActiveForm::end(); ?>
</div>
