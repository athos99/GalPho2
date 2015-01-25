<?php
/**
 * @var app\galpho\Galpho $galpho
 * @var yii\Web\View $this
 */

use \yii\helpers\Html;
use \yii\helpers\ArrayHelper;

$info = $galpho->getPathInfo();
$image = $galpho->getImageInfo();

$title = ArrayHelper::getValue($image, 'title');
$description = ArrayHelper::getValue($image, 'description');
$id=ArrayHelper::getValue($image,'id');

// breadcrumbs
$breadcrumbs = $galpho->getBreadcrumb();
end( $breadcrumbs);
$lastCrumb = key( $breadcrumbs);
array_pop($breadcrumbs);
foreach ($breadcrumbs as $key => $value) {
    echo '<a href="' . $value . '">' . \yii\helpers\Html::encode($key) . '</a> / ';
}
echo Html::encode($lastCrumb ) . '</br>';
?>

<br>
<h1><?php echo app\galpho\Helper::editable($title,
        ['pk' => $id, 'model' => 'GalElement', 'name' => 'title'], ''); ?></h1>
<p><?php echo app\galpho\Helper::editable($description,
        ['pk' => $id, 'model' => 'GalElement', 'name' => 'description'], '',['data-type'=>'textarea']); ?></p>




