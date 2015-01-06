<?php
/**
 * @var app\galpho\galpho $galpho
 * @var yii\Web\View $this
 */


/** @var \yii\web\request $request */
$request = Yii::$app->getRequest();

$url = $galpho->url;
$img = $request->getBaseUrl() . app\galpho\Galpho::IMG_THUMB_HEIGHT;


$fullList = $galpho->getPathList();
$pagination = new \yii\data\Pagination(['totalCount' => count($fullList),
    'pageSize' => 3,
    'route' => $galpho->route . '/' . $galpho->getPath(),
    'params' => $_REQUEST,
]);
$list = array_slice($fullList, $pagination->offset, $pagination->limit);

?>
<div class="row"><?php
    foreach ($list as $element) :
        if ($element['type'] == 'dir') :
            ?>
            <div class="galpho-thumb galpho-dir">
                <a class="thumbnail" href="<?php echo $url . $element['path']; ?>">
                    <img src="<?php echo $img . '/' . $element['cover']; ?>">
                </a>
                <a href="<?php echo $url . $element['path']; ?>">
                    <div><?php echo $element['title'] ?></div>
                </a>

                <div><?php echo $element['description'] ?></div>
            </div>
        <?php
        else :
            ?>
            <div class="galpho-thumb galpho-img">
                <a class="thumbnail" href="<?php echo $url . $element['path']; ?>">
                    <img src="<?php echo $img . '/' . $element['path']; ?>">
                </a>
                <a href="<?php echo $url . $element['path']; ?>">
                    <div><?php echo $element['title'] ?></div>
                </a>

                <div><?php echo $element['description'] ?></div>
            </div>
        <?php
        endif;
    endforeach;
    echo yii\Widgets\LinkPager::widget(['pagination' => $pagination,]);
    ?></div>