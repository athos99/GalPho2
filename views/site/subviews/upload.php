<?php
use yii\helpers\Html;
use yii\helpers\Url;


/**
 * @var yii\web\View $this
 */
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
