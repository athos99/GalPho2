<?php

namespace app\assets;

use yii\helpers\FileHelper;
use yii\helpers\Url;
use yii\web\AssetBundle;
use yii\web\AssetManager;

class AssetFileBundle extends AssetBundle
{
    /**
     * Convert before the file form the original dir and after publish the result
     *
     * @param $am  AssetManager $am the asset manager to perform the asset publishing
     */
    public function publish($am)
    {
        if (($this->sourcePath !== null) && ($converter = $am->getConverter()) !== null) {
            foreach ($this->js as $i => $js) {
                if (Url::isRelative($js)) {
                    $js = $converter->convert($js, $this->sourcePath);
                    $this->js[$i] = $this->myPublish($am,$this->sourcePath,$js);
//                    list ($basePath, $baseUrl) = $am->publish($this->sourcePath . '/' . $js);
//                    $this->js[$i] = ltrim($baseUrl,'/');
                }
            }
            foreach ($this->css as $i => $css) {
                if (Url::isRelative($css)) {
                    $css = $converter->convert($css, $this->sourcePath);
                    $this->css[$i]  = $this->myPublish($am,$this->sourcePath,$css);
//                    list ($basePath, $baseUrl)= $am->publish($this->sourcePath . '/' . $css);
//                    $this->css[$i] = ltrim($baseUrl,'/');
                }
            }
        }
        $this->baseUrl = $am->baseUrl;
    }


    public function myPublish( $am, $path, $file) {
        $dstFile = $am->basePath . DIRECTORY_SEPARATOR . $file;
        $srcFile = $path . DIRECTORY_SEPARATOR . $file;
        $dstDir = dirname( $dstFile);

        if (!is_dir($dstDir)) {
            FileHelper::createDirectory($dstDir, $am->dirMode, true);
        }

        if ($am->linkAssets) {
            if (!is_file($dstFile)) {
                symlink($srcFile, $dstFile);
            }
        } elseif (@filemtime($dstFile) < @filemtime($srcFile)) {
            copy($srcFile, $dstFile);
            if ($am->fileMode !== null) {
                @chmod($dstFile, $am->fileMode);
            }
        }

        return   $file;
    }

}