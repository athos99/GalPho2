<?php
/**
 * @var app\galpho\Galpho $galpho
 * @var yii\Web\View $this
 */
$this->blocks['header1'] = $this->render('//site/subviews/header-list', array('galpho' => &$galpho));
$this->blocks['block1'] = $this->render('//site/subviews/tree', array('galpho' => $galpho));
echo $this->render('//site/subviews/list', array('galpho' => $galpho));

use \yii\helpers\Html;
echo Html::beginForm();
echo athos99\plupload\PluploadWidget::widget(array());
echo Html::submitButton();
echo Html::endForm();
