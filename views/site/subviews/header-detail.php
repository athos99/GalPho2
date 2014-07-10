<?php
/**
 * @var app\galpho\Galpho $galpho
 * @var yii\Web\View $this
 */

use \yii\helpers\Html;
use \yii\helpers\ArrayHelper;

$info = $galpho->getPathInfo();
$title = ArrayHelper::getValue($info, 'title');
$description = ArrayHelper::getValue($info, 'description');

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
<h1> <?php echo Html::encode($title) ?></h1>
<p><?php echo Html::encode($description) ?></p>



