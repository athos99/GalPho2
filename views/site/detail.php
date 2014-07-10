<?php
use yii\helpers\Url;
use \yii\helpers\Html;

/**
 * @var app\galpho\Galpho $galpho
 * @var yii\Web\View $this
 */
$this->blocks['header1'] = $this->render('//site/subviews/header-detail', array('galpho' => &$galpho));
$this->blocks['block1'] = $this->render('//site/subviews/tree', array('galpho' => $galpho));

echo $this->blocks['header1'] ;
echo $this->blocks['block1'];


echo $this->render('//site/subviews/detail', array('galpho' => $galpho));
echo Html::beginForm();
echo athos99\plupload\PluploadWidget::widget(
    [
        'urlUpload' => Url::toRoute('/site/upload'),
        'data' => ['stamp' => uniqid()],
        //'baseStyle'=>'bootstrap'
    ]
);
echo Html::submitButton();
echo Html::endForm();
