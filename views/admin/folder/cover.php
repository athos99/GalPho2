<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use app\models\GalDir;
use app\galpho;

/**
 * @var yii\web\View $this
 * @var app\models\GalDir $model
 * @var ActiveForm $form
 * @var app\galpho\Galpho $galpho
 */
$request = Yii::$app->getRequest();
/** @var \app\models\GalElementBase $cover */
$cover = $model->coverElement;
$dir = $cover->dir;
$thumbDir = $request->getBaseUrl() . app\galpho\Galpho::IMG_THUMB_DIR;
$img = $thumbDir.$dir->path.'/'.$cover->name;
?>



<h2><?=yii::t('app','Cover');?></h2>
<div><img src=<?=$img?>></div>
<div><?=$dir->path.'/'.$cover->name?></div>
