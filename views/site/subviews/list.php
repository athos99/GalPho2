<?php
/**
 * @var app\galpho\galpho $galpho
 * @var yii\Web\View $this
 */


/** @var \yii\web\request $request */
$request = Yii::$app->getRequest();

$url = Yii::$app->getUrlManager()->createUrl('v');
$img = $request->getBaseUrl() . app\galpho\Galpho::IMG_THUMBNAIL;


$fullList = $galpho->getPathList();
$pagination = new \yii\data\Pagination(['totalCount' => count($fullList),
    'pageSize' => 3,
    'route' => $galpho->route.'/'. $galpho->getPath(),
    'params' => $_REQUEST,
]);
$list = array_slice($fullList, $pagination->offset, $pagination->limit);



foreach ($list as $element) :
    if ($element['type'] == 'dir') :
        ?>
        <div>
            <img src="<?php echo $img . '/'.$element['cover']; ?>">
            <a href="<?php echo $url .'/'. $element['path']; ?>">
                <div><?php echo $element['title'] ?></a></div>
        <div><?php echo $element['description'] ?></div>
        </div>
    <?php
    else :
        ?>
        <div>
            <img src="<?php echo $img . '/'. $element['path']; ?>">
            <a href="<?php echo $url . '/'. $element['path']; ?>">
                <div><?php echo $element['title'] ?></a></div>
        <div><?php echo $element['description'] ?></div>
        </div>
    <?php
    endif;
endforeach;
echo yii\Widgets\LinkPager::widget(['pagination' => $pagination,]);