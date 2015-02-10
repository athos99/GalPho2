<?php
use \yii\helpers\Html;
use \yii\helpers\ArrayHelper;
/**
 * @var app\galpho\Galpho $galpho
 * @var yii\Web\View $this
 * @var string $editIcon
 */
$info = $galpho->getPathInfo();
$title = ArrayHelper::getValue($info, 'title');
$description = ArrayHelper::getValue($info, 'description');
?><p><?php
echo $editIcon;
// breadcrumbs
$breadcrumbs = $galpho->getBreadcrumb();
end( $breadcrumbs);
$lastCrumb = key( $breadcrumbs);
array_pop($breadcrumbs);
foreach ($breadcrumbs as $key => $value) {
    echo '<a href="' . $value . '">' . \yii\helpers\Html::encode($key) . '</a> / ';
}
?>
<?=Html::encode($lastCrumb )?></p>
<h1 style="page-title"><?=Html::encode($title) ?></h1>
<p class="lead"><?=Html::encode($description);?></p>