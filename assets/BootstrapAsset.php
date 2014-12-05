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
    ];

    public function init()
    {
        parent::init();
        $parser = new \athos99\assetparser\Less();

        $parser->parse(Yii::getAlias('@app/assets/bootstrap/less/bootstrap.less'), Yii::getAlias($this->sourcePath) . '/css/bootstrap.css', []);

    }

}
