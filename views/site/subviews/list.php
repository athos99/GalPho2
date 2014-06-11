<?php
/**
 * @var app\Galpho $galpho
 * @var yii\Web\View $this
 */


/** @var \yii\web\request $request */
$request = Yii::$app->getRequest();

$url = yii::$app->getUrlManager()->createUrl('v');
$img = $request->getBaseUrl() . app\Galpho::IMG_THUMBNAIL;


$fullList = $galpho->getPathList();
$pagination = new \yii\data\Pagination(array('totalCount' => count($fullList),
    'pageSize' => 3,
    'route' => 'v' . $galpho->getPath(),
    'params' => $_REQUEST,
));
$list = array_slice($fullList, $pagination->offset, $pagination->limit);


$x = Yii::$app->controller->getRoute();
foreach ($list as $element) :
    if ($element['type'] == 'dir') :
        ?>
        <div>
            <img src="<?php echo $img . $element['cover']; ?>">
            <a href="<?php echo $url . $element['path']; ?>">
                <div><?php echo $element['title'] ?></a></div>
        <div><?php echo $element['description'] ?></div>
        </div>
    <?php
    else :
        ?>
        <div>
            <img src="<?php echo $img . $element['cover']; ?>">
            <a href="<?php echo $url . $element['path']; ?>">
                <div><?php echo $element['title'] ?></a></div>
        <div><?php echo $element['description'] ?></div>
        </div>
    <?php
    endif;
endforeach;
echo yii\Widgets\LinkPager::widget(['pagination' => $pagination,]);