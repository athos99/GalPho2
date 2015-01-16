<?php

class AdminTool
{


    public function generateImage($path, $width, $height)
    {

        $rect = [
            [0, 0, .5, .5],
            [.5, .0, 1, .5],
            [0, .5, .5, 1],
            [.5, .5, 1, 1],
        ];
        $img = imagecreatetruecolor($width, $height);
        $backgroundColor = imagecolorallocate($img, 255, 255, 255);
        imagefill($this->img, 0, 0, $backgroundColor);

        foreach ($rect as $v) {
            $c = imagecolorallocate($img, rand(0, 255), rand(0, 255), rand(0, 255));
            imagefilledrectangle($img, intval(width * $v[0]), intval($height * $v[1]), intval(width * $v[2]), intval($height * $v[3]),$c);
            imagecolordeallocate($img, $c);
        }
        $c = imagecolorallocate($img, rand(0, 255), rand(0, 255), rand(0, 255));
        imag


        $foregroundColor = imagecolorallocate($this->img, 255, 0, 0);
        imagepolygon($this->img, [
                0, 0,
                $width - 1, 0,
                $width - 1, $height - 1,
                0, $height - 1,
            ],
            4, $foregroundColor);
        imageline($this->img, 0, 0, $width - 1, $height - 1, $foregroundColor);
        imageline($this->img, $width - 1, 0, 0, $height - 1, $foregroundColor);

    }


}