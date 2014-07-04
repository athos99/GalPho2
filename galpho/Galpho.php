<?php
namespace app\galpho;

use Yii;
use yii\base\Component;
use yii\base\Exception;
use yii\helpers\ArrayHelper;
use yii\helpers\BaseInflector;
use app\models;
use yii\helpers\FileHelper;

class Galpho extends component
{

    const VIEW_UNKNOW = 0;
    const VIEW_LIST = 1;
    const VIEW_DETAIL = 2;


    const IMG_STANDARD = '/img';
    const IMG_THUMBNAIL = '/img/1';
    const IMG_SMALL_THUMB = '/img/2';


    public $url;
    public $_path;
    public $_idPath;
    public $_idGroups=[];

    public $_fullStructure;
    public $_pathStructure;
    public $_elementName;
    public $_elementBase;


    public $_viewMode = self::VIEW_UNKNOW;


    public function init()
    {
        parent::init();
        $this->url = \yii::$app->getUrlManager()->createUrl('v');
    }

    public function setPath($path)
    {
        $this->_path = '/' . trim($path, '/');
        $this->_pathStructure = null;
        $this->_idPath = null;
    }

    public function getPath()
    {
        return $this->_path;
    }


    public function getIdPath()
    {
        $this->getPathStructure();
        return $this->_idPath;
    }

    public function getFullStructure()
    {
        if ($this->_fullStructure === null) {
            $this->_fullStructure = models\Galpho::getCacheStructure($this->_idGroups);
        }
        return $this->_fullStructure;
    }

    public function getPathStructure()
    {
        $this->getFullStructure();
        if ($this->_pathStructure === null) {
            $this->_viewMode = self::VIEW_LIST;
            $this->_elementName = null;
            $this->_elementBase = $this->_path;
            $this->_pathStructure = models\Galpho::findPath($this->_fullStructure, $this->_path);
            if ($this->_pathStructure === false) {
                $this->_pathStructure = models\Galpho::findPath($this->_fullStructure, dirname($this->_path));
                if ($this->_pathStructure !== false) {
                    $this->_viewMode = self::VIEW_DETAIL;
                    $this->_elementName = basename($this->_path);
                    $this->_elementBase = dirname($this->_path);
                }

            }
            $this->_idPath = isset($this->_pathStructure['#']['id']) ? $this->_pathStructure['#']['id'] : null;
        }
        return $this->_pathStructure;
    }


    public function getViewMode()
    {
        $this->getPathStructure();
        return $this->_viewMode;
    }

    public function setGroups($idGroups)
    {
        $this->_idGroups = $idGroups;
        $this->_pathStructure = null;
        $this->_fullStructure;
    }

    public function getPathInfo()
    {
        $this->getPathStructure();
        if (isset($this->_pathStructure['#'])) {
            return $this->_pathStructure['#'];
        }
        return array();
    }


    public function getImageInfo()
    {
        $this->getPathStructure();
        $elements = models\Galpho::getCacheElementsForDir($this->_idPath);
        if (isset($elements[$this->_elementName])) {
            $element = $elements[$this->_elementName];
            return array(
                'id' => $element->id,
                'title' => $element->title,
                'path' => $this->_elementBase . '/' . $element->name,
                'description' => $element->description,
                'createTime' => $element->create_time,
            );
        }
        return false;
    }


    public function getPathList()
    {
        $this->getPathStructure();
        $list = array();
        if (is_array($this->_pathStructure)) {
            foreach ($this->_pathStructure as $key => $dir) {
                if ($key === '#') {
                    continue;
                }
                if (isset($dir['#'])) {
                    $list[] = $dir['#'] + array('type' => 'dir');
                }
            }
        }
        if ($this->_idPath !== null) {
            $galElements = models\Galpho::getCacheElementsForDir($this->_idPath);
            foreach ($galElements as $galElement) {
                $list[] = array(
                    'id' => $galElement->id,
                    'title' => $galElement->title,
                    'path' => $this->_path . '/' . $galElement->name,
                    'cover' => $this->_path . '/' . $galElement->name,
                    'description' => $galElement->description,
                    'createTime' => $galElement->create_time,
                    'type' => 'img');
            }
        }
        return $list;
    }


    public function getImgPathList()
    {
        $this->getPathStructure();
        $list = array();
        if ($this->_idPath !== null) {
            $galElements = models\Galpho::getCacheElementsForDir($this->_idPath);
            foreach ($galElements as $galElement) {
                $list[] = array(
                    'id' => $galElement->id,
                    'title' => $galElement->title,
                    'path' => $this->_path . '/' . $galElement->name,
                    'cover' => $this->_path . '/' . $galElement->name,
                    'description' => $galElement->description,
                    'createTime' => $galElement->create_time,
                    'type' => 'img');
            }
        }
        return $list;
    }

    public function addElement($filename, $name)
    {
        $exif = null;
        $dst = Yii::getAlias('@app/'.Yii::$app->params['image']['src']) . $this->getPath();
        try {
            $mime = FileHelper::getMimeType($filename);
            if ($mime == "image/jpeg") {
                $exif = new \app\galpho\Exif($filename);
            }
        } catch (Exception $e) {

        }

        if (!is_dir($dst)) {
            mkdir($dst, 777, true);
        }
        $out = @fopen($dst . $name, "wb");
        if ($out) {
            // Read binary input stream and append it to temp file
            $in = @fopen($filename, "rb");
            if ($in) {
                while ($buff = fread($in, 4096)) {
                    fwrite($out, $buff);
                }
            } else {
                @fclose($in);
                @fclose($out);
                @unlink($out);
                return false;
            }
            @fclose($in);
            @fclose($out);
            @unlink($filename);


            $element = new models\GalElement();
            $element->name = $name;
            $element->title = $name;
            $element->format = 'image';
            $element->dir_id = $this->getIdPath();
            $element->create_time = new \yii\db\Expression('NOW()');
            $element->update_time = new \yii\db\Expression('NOW()');
            $tick = null;
            if (isset($exif)) {
                if (ctype_digit($exif->caption)) {
                    $tick =strtotime($exif->caption);
                    if ( $tick) {

                    $element->create_time = $exif->caption;
                    }

                }
                $element->description = json_encode([
                    'caption'=>$tick ? date('d.m.Y H:i',$tick) : '',
                    'aperture'=>$exif->aperture,
                    'speed'=>$exif->speed,
                    'iso'=>$exif->iso,
                    'focal'=>$exif->focal,
                    'model'=>$exif->model

                ]);
            }
            $element->save();
        }
    }


    public function getBreadcrumb()
    {
        $list = array();
        $structure = $this->_fullStructure;
        foreach (explode('/', trim($this->_elementBase, '/')) as $key) {
            if (isset($structure['#'])) {
                $list[$structure['#']['title']] = $this->url . $structure['#']['path'];
                $structure = & $structure[$key];
            }
        }
        if (isset($structure['#'])) {
            $list[$structure['#']['title']] = $this->url . $structure['#']['path'];
            $structure = & $structure[$key];
        }
        if (isset($this->_elementName)) {
            $info = $this->getImageInfo();
            $list[$info['title']] = $this->url . $info['path'];
        }
        return $list;
    }


    protected function _subRepairFolder(&$structure)
    {
        if (!isset($structure['#'])) {
            // bug, there is no father folder records !
            $galDir = new models\GalDir();
            $this->setPath(reset($structure)['#']['cover']);
            $cover = $this->getImageInfo();
            if ($cover !== false) {
                $galDir->element_id_cover = $cover['id'];
            }
            $galDir->path = dirname(reset($structure)['#']['path']);
            $galDir->title = BaseInflector::titleize(basename($galDir->path));
            $galDir->create_time = new \yii\db\Expression('NOW()');
            $galDir->update_time = new \yii\db\Expression('NOW()');

            $galDir->save();
        }
        foreach ($structure as $key => $dir) {

            if ($key === '#') {
                continue;
            }

            $this->_subRepairFolder($dir);
        }
    }

    public function repair()
    {
        $path = $this->getPath();
        models\DbTableDependency::reset();
        $this->_fullStructure = null;
        $this->getPathStructure();
        $pathStructure = $this->_fullStructure;
        $this->_subRepairFolder($pathStructure);
        models\DbTableDependency::reset();
        $this->_fullStructure = null;
        $this->setPath($path);
        $this->getPathStructure();
    }
}