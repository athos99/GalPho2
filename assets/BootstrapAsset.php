<?php
namespace app\assets;

use yii;
use yii\web\AssetBundle;

class BootstrapAsset extends AssetBundle
{
    public $sourcePath = '@app/assets/bootstrap/dist';
    public $css = [
        'css/bootstrap.css',
    ];
    public $js = [
        'js/bootstrap.js',
        'js/tooltip.js',
        'js/popover.js'

    ];
    public $depends = [
        'yii\web\JqueryAsset',
    ];

    public function init()
    {
        parent::init();
        $dstDir = Yii::getAlias($this->sourcePath) . '/css';
        if (!is_dir($dstDir)) {
            yii\helpers\FileHelper::createDirectory($dstDir);
        }

        $parser = new \athos99\assetparser\Less();
        $update = $parser->parse(Yii::getAlias('@app/assets/bootstrap/less/bootstrap.less'), $dstDir . '/bootstrap.css', []);
        $this->publishOptions['forceCopy'] = $update;
    }

}
