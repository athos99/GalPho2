<?php
/**
 * @var app\galpho\Galpho $galpho
 * @var yii\Web\View $this
 */

$url = yii::$app->getUrlManager()->createUrl('v').'/';
$list = $galpho->getImgPathList();

$size = 10;
$halfSize = 5;


foreach ($list as $key => $element) {
    $offset = 0;
    if ($element['path'] == $galpho->getPath()) {
        $offset = key;
        break;
    }
    $selectList = array_slice( $list ,max( $halfSize, $offset-$halfSize-max(count($list)-$offset),0), $size);
}

foreach ($galpho->getPathList() as $element) :
    echo '<br>title:' . $element['title'];
    echo '<br>description:' . $element['description'];
    echo '<br>path:' . $element['path'];
    echo '<br>cover:' . $url . $element['cover'];




endforeach;
