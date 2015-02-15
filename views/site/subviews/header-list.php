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
?><div class="page-breadcrumb"><?php
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
<?=Html::encode($lastCrumb )?></div>
<div class="page-title"><?=Html::encode($title) ?></div>
<div class="page-description"><?=Html::encode($description);?></div>