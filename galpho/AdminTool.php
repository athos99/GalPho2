<?php
namespace app\galpho;

use Yii;
use yii\helpers\BaseInflector;

class AdminTool extends Galpho
{


    public function generateImage($file, $width, $height)
    {
        $rect = [
            [0, 0, .5, .5],
            [.5, .0, 1, .5],
            [0, .5, .5, 1],
            [.5, .5, 1, 1],
        ];
        $img = imagecreatetruecolor($width, $height);
        $backgroundColor = imagecolorallocate($img, 255, 255, 255);
        imagefill($img, 0, 0, $backgroundColor);

        foreach ($rect as $v) {
            $c = imagecolorallocate($img, rand(0, 255), rand(0, 255), rand(0, 255));
            imagefilledrectangle($img, intval($width * $v[0]), intval($height * $v[1]), intval($width * $v[2]), intval($height * $v[3]), $c);
            imagecolordeallocate($img, $c);
        }
        $c = imagecolorallocate($img, rand(0, 255), rand(0, 255), rand(0, 255));
        imagefilledellipse($img, intval($width * .5), intval($height * .5), intval($width * .5), intval($height * .5), $c);
        imagecolordeallocate($img, $c);
        $dir = dirname($file);
        if (!is_dir($dir)) {
            mkdir($dir, 777, true);
        }
        imagejpeg($img, $file);
        @chmod($file, 0777);
        imagedestroy($img);
    }

    public function createImage($width, $height, $name)
    {
        $src = trim(Yii::getAlias('@app/' . Yii::$app->params['image']['src'] . '/' . $this->getPath()), '/');
        $file = $src . '/' . $name . '.jpg';
        if (is_file($file)) {
            unlink($file);
        }
        $this->generateImage($file, $width, $height);
        return $file;
    }


    public function generateDir($path, $level, $nbDir, $nbImage, $name)
    {
        if ($level > 0) {

            $this->setPath($path);
            $slug = BaseInflector::slug($name, '-', true);

            $description = "Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua.";
            $dirId = $this->addFolder($slug, $name, $description);
            $this->setIdPath($dirId);
            if ($nbImage > 0) {
                $i = rand(1, $nbImage);
            } else {
                $i = -$nbImage;
            }


            while ($i-- > 0) {
                $imgName = 'img_00'.$i;
                if (rand(0, 1)) {
                    $file = $this->createImage(480, 720, $imgName);
                } else {
                    $file = $this->createImage(720, 480, $imgName);
                }
                $this->addElement($file, null, null);
            }
            $i = rand(1, $nbDir);
            $path = $this->getPath();
            while ($i-- > 0) {
                // $name = uniqid();
                $this->generateDir($path, $level - 1, $nbDir, $nbImage, $name . '_' . $i);
            }
        }
    }


    public function generateGallery()
    {

        $this->generateDir('', 9, 2, 1, 'deep');
        $this->generateDir('', 1, 0, -100, 'big');
        $this->generateDir('', 2, 2, 2, 'very long name');
        for ($i = 0; $i < 20; $i++) {
            $this->generateDir('', 2, 5, 3, 'dir' . $i);
        }
        $this->clearCache();
    }
}