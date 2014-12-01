<?php
namespace app\assets;

use yii\web\AssetBundle;

class AdminAsset extends AssetFileBundle
{
    public $sourcePath = '@app/assets';
    public $css = [
    ];
    public $js = [
        'js/galpho.js',
        'js/replace-diacritics.js',
        'js/listorder.js',
        'js/replace-diacritics.js'

    ];
    public $depends = [
        'app\assets\XeditableAsset',
        'yii\web\JqueryAsset',
    ];
}
