<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use app\models\GalDir;
use app\galpho;

/**
 * @var yii\web\View $this
 * @var app\models\GalDir $model
 * @var app\models\GalElement[] $galElements
 * @var ActiveForm $form
 * @var app\galpho\Galpho $galpho
 */

$request = Yii::$app->getRequest();
/** @var \app\models\GalElementBase $cover */
$cover = $model->coverElement;
$dir = $cover->dir;
$thumbDir = $request->getBaseUrl() . app\galpho\Galpho::IMG_SMALL_THUMB;
$img = $thumbDir . $dir->path . '/' . $cover->name;

?>
    <table class="table">

        <?php foreach ($galElements as $element) :
            if ($element->format == 'image') :
                ?>
                <tr>
                <td width="25%">
                    <h4><?= yii::t('app', 'Cover'); ?></h4>

                    <div><img src=<?=
                        $thumbDir . $model->path . '/' . $element->name;
                        ?>></div>
                </td>
                <td width="25%">


                    <?=
                    $form->field($element, 'title')->widget(galpho\MultiLingualInput::className(), [
                        'languages' => $galpho->getShortLanguages(),
                        'labelOptions' => ['class' => 'col-sm-2 control-label'],
                        'inputOptions' => ['class' => 'form-control'],
                        'divInputOptions' => ['class' => 'col-sm-10 form-group-sm']
                    ]);?>
                </td>
                <td width="25%">
                    <div class="xxxform-horizontal">
                        <?=
                        $form->field($element, 'description')->widget(galpho\MultiLingualInput::className(), [
                            'languages' => $galpho->getShortLanguages(),
                            'type' => 'textarea',
                            'labelOptions' => ['class' => 'col-sm-2 control-label'],
                            'inputOptions' => ['class' => 'form-control', 'cols' => '3'],
                            'divInputOptions' => ['class' => 'col-sm-10 form-group-sm']
                        ]);?></div>
                </td>
                </tr>

                ?>
            <?php
            endif;
        endforeach ?>
    </table>

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

