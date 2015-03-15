<?php
namespace app\galpho;

class Exif
{

    public $w = 0;
    public $h = 0;
    public $caption = NULL;
    public $aperture = '';
    public $speed = '';
    public $model = '';
    public $iso = '';
    public $focal = '';
    public $tags = [];



    public function __construct($filename)
    {
        $size = getimagesize($filename, $info);

// treatment of IPTC information for tag
        if (isset($info["APP13"])) {
            $iptc = iptcparse($info["APP13"]);
            if (isset($iptc['2#025'])) {
                foreach ($iptc['2#025'] as $value) {
                    $this->tags[] = $value;
                }
            }
        }


// read exif value
        if ($exif = @exif_read_data($filename, 'ANY_TAG', true)) {
            if (isset($exif['IFD0']['Model'])) {
                $this->model = $exif['IFD0']['Model'];
            }
            if (isset($exif['EXIF']['FNumber'])) {
                $v=$exif['EXIF']['FNumber'];
                if (!is_numeric($v)) {
                    $v = explode('/',$v);
                    if ( count($v)==2 and !empty($v[1])) {
                        $v = $v[0]/$v[1];
                    } else {
                        $v=0;
                    }
                }
                $this->aperture = 'f/' . number_format($v, 1);
            }
            if (isset($exif['EXIF']['ExposureTime'])) {
                $v=$exif['EXIF']['ExposureTime'];
//                if (!is_numeric($v)) {
//                    $v = explode('/',$v);
//                    if ( count($v)==2 and !empty($v[1])) {
//                        $v = $v[0]/$v[1];
//                    } else {
//                        $v=0;
//                    }
//                }
                $this->speed = $v . 's';
            }
            if (isset($exif['EXIF']['ISOSpeedRatings'])) {
                $this->iso = $exif['EXIF']['ISOSpeedRatings'];
            }
            if (isset($exif['EXIF']['FocalLength'])) {
                $v=$exif['EXIF']['FocalLength'];
                if (!is_numeric($v)) {
                    $v = explode('/',$v);
                    if ( count($v)==2 and !empty($v[1])) {
                        $v = $v[0]/$v[1];
                    } else {
                        $v=0;
                    }
                }
                $this->focal = number_format($v, 1) . 'mm';
            }
            if (isset($exif['EXIF']['DateTimeOriginal'])) {

                $this->caption = str_replace([' ', ':'], '', $exif['EXIF']['DateTimeOriginal']);
            }
        }

// read image dimension
        if (is_array($size)) {
            if (isset($size[0])) {
                $this->w = $size[0];
            }
            if (isset($size[1])) {
                $this->h = $size[1];
            }
        }
    }
}