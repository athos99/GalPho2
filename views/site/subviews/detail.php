<?php
/**
 * @var app\galpho\Galpho $galpho
 * @var yii\Web\View $this
 */



use \yii\helpers\Html;
use \yii\helpers\ArrayHelper;

/** @var \yii\web\request $request */
$request = Yii::$app->getRequest();

/** @var \app\galpho\Galpho $galpho */
$info = $galpho->getPathInfo();
$image = $galpho->getImageInfo();
$img = $request->getBaseUrl() . '/img';


$title = ArrayHelper::getValue($image, 'title');
$description = ArrayHelper::getValue($image, 'description');
?>
<div>
    <img src="<?php echo $img . $image['path']; ?>">
</div>


<br>
title : <?php echo Html::encode($title) ?><br>
descritption : <?php echo Html::encode($description) ?><br>





