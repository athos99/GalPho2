<?php
namespace app\assets;

use yii\web\AssetBundle;

class BootstrapAsset extends AssetBundle
{
    public $sourcePath = '@bower/bootstrap';
    public $js = [
        'js/tooltip.js',
        'js/popover.js'

    ];
}
