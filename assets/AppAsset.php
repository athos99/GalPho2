<?php
namespace app\assets;
use yii;
use yii\web\AssetBundle;

class AppAsset extends AssetBundle
{
    public $sourcePath = '@app/assets/galpho';
    public $css = [
//        'site.css',
        'galpho.less'
    ];
    public $js = [
        'galpho.js',
        'replace-diacritics.js',
        'listorder.js',

    ];
    public $depends = [
        'yii\web\YiiAsset',
        'app\assets\BootstrapAsset',
//       'app\assets\AdminAsset',
        'app\assets\XeditableAsset',
//        'yii\web\JqueryAsset',
    ];


public function init() {
    parent::init();
}

}
