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
    <div class="col-md-2">
        <?= $this->blocks['block1']; ?>
    </div>
    <div class="col-md-10">
        <?= $this->blocks['header']; ?>
        <?= $this->blocks['block2']; ?>
        <?= $this->blocks['block3']; ?>
    </div>

</div>