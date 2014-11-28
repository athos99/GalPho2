<?php
namespace app\assets;

use yii\web\AssetBundle;

class AppAsset extends AssetBundle
{
    public $sourcePath = '@app/assets';
    public $css = [
        'site.css',
        'galpho.less'
    ];
    public $js = [
        'galpho.js',
        'replace-diacritics.js'

    ];
    public $depends = [
        'yii\web\YiiAsset',
        'app\assets\BootstrapAsset',

//        'yii\bootstrap\BootstrapAsset',
        'app\assets\XeditableAsset'
    ];
}
