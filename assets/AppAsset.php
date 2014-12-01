<?php
namespace app\assets;

use yii\web\AssetBundle;

class AppAsset extends AssetFileBundle
{
    public $sourcePath = '@app/assets';
    public $css = [
        'mybootstrap/less/bootstrap.less',
        'css/site.css',
        'less/galpho.less'
    ];
    public $js = [
        'js/galpho.js',

    ];
    public $depends = [
        'yii\web\YiiAsset',
        'app\assets\AdminAsset',
        'app\assets\BootstrapAsset',
    ];
}
