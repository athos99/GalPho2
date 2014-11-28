<?php
namespace app\assets;

use yii\web\AssetBundle;

class XeditableAsset extends AssetBundle
{
    public $sourcePath = '@vendor/vitalets/x-editable/dist/bootstrap3-editable';
    public $css = [
        'css/bootstrap-editable.css'
    ];
    public $js = [
        'js/bootstrap-editable.js'

    ];
    public $depends = [
//        'yii\bootstrap\BootstrapAsset',
    ];


}
