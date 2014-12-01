<?php

namespace app\assets;

use yii\helpers\Url;
use yii\web\AssetBundle;

class AssetFileBundle extends AssetBundle
{
    /**
     * Convert before the file form the original dir and after publish the result
     *
     * @param AssetManager $am the asset manager to perform the asset publishing
     */
    public function publish($am)
    {
        if (($this->sourcePath !== null) && ($converter = $am->getConverter()) !== null) {
            foreach ($this->js as $i => $js) {
                if (Url::isRelative($js)) {
                    $js = $converter->convert($js, $this->sourcePath);
                    list ($basePath, $baseUrl) = $am->publish($this->sourcePath . '/' . $js);
                    $this->js[$i] = ltrim($baseUrl,'/');
                }
            }
            foreach ($this->css as $i => $css) {
                if (Url::isRelative($css)) {
                    $css = $converter->convert($css, $this->sourcePath);
                    list ($basePath, $baseUrl)= $am->publish($this->sourcePath . '/' . $css);
                    $this->css[$i] = ltrim($baseUrl,'/');
                }
            }
            $this->baseUrl = '';
        }
    }
}