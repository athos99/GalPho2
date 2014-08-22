<?php
namespace app\assets;
use yii\web\AssetBundle;

class ListorderAsset extends AssetBundle
{
    public $sourcePath = '@app/assets';
    public $js = [
        'listorder.js'
    ];
    public $depends = [
        'yii\web\JqueryAsset',
    ];
}
