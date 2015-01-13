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
$info = $galpho->getPathInfo();
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
            <div class="galpho-thumb galpho-dir thumbnail">
                <div class="photo">
                    <a href="<?= $url . $element['path']; ?>">
                        <img src="<?php echo $img . '/' . $element['cover']; ?>">
                    </a>


                    <div class="zone zone1">
                       <h3>


                           <i class="glyphicon glyphicon-folder-open"></i><small><?=yii::t('app','{nb, plural, =0{no image} =1{1 image} other{# images}}',['nb'=>$element['tot_e']]);?></small>
                        </h3>
                 </div>
                    <div class="zone zone2">
                        <h3>
                        <a href="<?= $url . $element['path']; ?>">
                            <?= $element['title']; ?>
                        </a>
                        </h3>
                    </div>
                </div>
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