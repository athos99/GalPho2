<?php
/**
 * @var app\galpho\galpho $galpho
 * @var yii\Web\View $this
 */


/** @var \yii\web\request $request */
$request = Yii::$app->getRequest();

$url = $galpho->url;
$thumbDir = $request->getBaseUrl() . app\galpho\Galpho::IMG_THUMB_DIR;
$thumbImg = $request->getBaseUrl() . app\galpho\Galpho::IMG_THUMB_IMG;


$fullList = $galpho->getListForFolder();
$info = $galpho->getPathInfo();
$pagination = new \yii\data\Pagination(['totalCount' => count($fullList),
    'pageSize' => 25,
    'route' => $galpho->route . '/' . $galpho->getPath(),
    'params' => $_REQUEST,
]);
$list = array_slice($fullList, $pagination->offset, $pagination->limit);
    $this->beginBlock('dirList');
    foreach ($list as $element) :
        if ($element['type'] == 'dir') :
            ?>
            <div class="galpho-thumb galpho-thumb-dir">
                <div class="photo ">
                    <a href="<?= $url . $element['path']; ?>">
                        <img src="<?php echo $thumbDir . '/' . $element['cover']; ?>">
                    </a>

                    <div class="zone zone1">
                        <i class="glyphicon glyphicon-folder-open"></i>&nbsp;&nbsp;<?= yii::t('app', '{nb, plural, =0{no image} =1{1 image} other{# images}}', ['nb' => $element['tot_e']]); ?>
                    </div>
                    <div class="zone zone2">
                        <a href="<?= $url . $element['path']; ?>">
                            <?= $element['title']; ?>
                        </a>
                    </div>
                </div>
            </div>
        <?php
        endif;
    endforeach;
    $this->endBlock();
    $this->beginBlock('imgList');
    foreach ($list as $element) :
        if ($element['type'] == 'img') :
            ?>
            <div class="galpho-thumb galpho-thumb-img">
                <div class="photo">
                    <a class="photo" href="<?php echo $url . $element['path']; ?>">
                        <img src="<?php echo $thumbImg . '/' . $element['path']; ?>">
                    </a>

                    <div class="zone zone2">
                        <a href="<?= $url . $element['path']; ?>">
                            <?= $element['title']; ?>
                        </a>
                    </div>
                </div>
            </div>
        <?php
        endif;
    endforeach;
    $this->endBlock();
    if ($this->blocks['dirList'] <> '') :
        ?>
        <div class="galpho-dir clearfix jumbotron"><h2><?= Yii::t('app','galleries');?></h2><?= $this->blocks['dirList'] ?> </div><?php
    endif;
    if ($this->blocks['imgList'] <> '') :
        ?>
        <div class="galpho-img clearfix jumbotron"><h2><?= Yii::t('app','images');?></h2><?= $this->blocks['imgList'] ?> </div><?php
    endif;
    echo yii\Widgets\LinkPager::widget(['pagination' => $pagination,]);
