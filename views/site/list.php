<?php
use \yii\helpers\Url;
use app\galpho\Helper;


/**
 * @var \app\galpho\Galpho $galpho
 * @var \yii\Web\View $this
 */

$editIcon =  app\galpho\Helper::dialog(Url::to(['/admin/folder/index', 'id' => $galpho->getIdPath()]),'',null);
$this->blocks['header'] = $this->render('//site/subviews/header-list', ['galpho' => &$galpho, 'editIcon'=>&$editIcon]);
$this->blocks['block1'] = $this->render('//site/subviews/tree', ['galpho' => &$galpho]);
$this->blocks['block2'] = $this->render('//site/subviews/list', ['galpho' => &$galpho]);
$this->blocks['block3'] = $this->render('//site/subviews/upload', ['galpho' => &$galpho]);
?>
<div class="row">
    <div class="col-md-10 col.md-push-2">
        <?= $this->blocks['header']; ?>
        <div id="<?=Helper::pJax();?>">
        <?= $this->blocks['block2']; ?>
            </div>
    </div>
    <div class="col-md-2 col.md-pull-10">
        <?= $this->blocks['block1']; ?>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <?= $this->blocks['block3']; ?>
    </div>

</div>
