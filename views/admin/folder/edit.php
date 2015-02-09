<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use app\models\GalDir;
use app\galpho;

/*
 * @var yii\web\View $this
 * @var app\models\GalDir $model
 * @var ActiveForm $form
 * @var app\galpho\Galpho $galpho
 */
?>

<?= $form->field($model, 'title')->widget(galpho\MultiLingualInput::className(), [
    'languages' => $galpho->getLanguages(),
    'labelOptions' => ['class' => 'col-sm-2 control-label'],
    'inputOptions' => ['class' => 'form-control'],
    'divInputOptions' => ['class' => 'col-sm-10']
]);?>
<?=
$form->field($model, 'description')->widget(galpho\MultiLingualInput::className(), [
    'languages' => $galpho->getLanguages(),
    'type' => 'textarea',
    'labelOptions' => ['class' => 'col-sm-2 control-label'],
    'inputOptions' => ['class' => 'form-control'],
    'divInputOptions' => ['class' => 'col-sm-10']
]);?>
<?= $form->field($model, 'url') ?>
<?= $form->field($model, 'auto_path')->checkbox() ?>

<?php

$js = <<<eot
function _urlFolder() {
  if ($('#galdir-auto_path').prop("checked")) {
  $('#galdir-url').val(replaceDiacritics($('#galdir-title').val().toLowerCase().replace(/[\s]+/g, '-')).replace(/[^a-z0-9-_]+/g, ''));
  }
}
$('#galdir-title').on('change.galpho keyup.galpho', _urlFolder);
$('#galdir-auto_path').on('change.galpho', _urlFolder);
eot;


$this->registerJs($js, yii\web\View::POS_READY, null);

