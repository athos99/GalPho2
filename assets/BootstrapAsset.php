<?php
namespace app\assets;

use yii\web\AssetBundle;

class BootstrapAsset extends AssetBundle
{
    public $sourcePath = '@bower/bootstrap';
    public $css = [
        'less/bootstrap.less'
    ];
    public $js = [
        'js/tooltip.js',
        'js/popover.js'

    ];
    public $depends = [
    ];


}
