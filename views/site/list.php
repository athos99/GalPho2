<?php
use \yii\helpers\Html;

/**
 * @var app\galpho\Galpho $galpho
 * @var yii\Web\View $this
 */
$this->blocks['header'] = $this->render('//site/subviews/header-list', ['galpho' => &$galpho]);
$this->blocks['block1'] = $this->render('//site/subviews/tree', ['galpho' => $galpho]);
$this->blocks['block2'] = $this->render('//site/subviews/list', ['galpho' => $galpho]);
$this->blocks['block3'] = $this->render('//site/subviews/upload', ['galpho' => $galpho]);


?>
<div class="row">
    <div class="col-md-10 col.md-push-2">
        <?= $this->blocks['header']; ?>
        <?= $this->blocks['block2']; ?>
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