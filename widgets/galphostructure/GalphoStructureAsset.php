<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\widgets\galphostructure;
use yii\web\AssetBundle;

/**
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class GalphoStructureAsset extends AssetBundle
{
   public $sourcePath = '@app/widgets/galphostructure/assets';
    public $baseUrl = '@web';
	public $js = [
		'galphoStructure.js',
	];
    public $css = [
        'galphoStructure.less',
    ];
	public $depends = [
   //     'app\assets\AppAsset',
        'yii\web\JqueryAsset',
	];
}
