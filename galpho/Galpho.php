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


    const IMG_THUMB_DIR = '/img/1';
    const IMG_THUMB_IMG = '/img/2';
    const IMG_SMALL_THUMB = '/img/3';
    const IMG_STANDARD = '/img/5';


    public $url;
    public $route;
    public $_path = ''; // current path
    public $_idPath;
    public $_idGroups = [];

    public $_fullStructure;
    public $_pathStructure;
    public $_elementName;
    public $_elementBase;


    public $_viewMode = self::VIEW_UNKNOW;


    public function init()
    {
        parent::init();
        $this->route = 'v';
        $this->url = Yii::$app->getUrlManager()->createUrl($this->route) . '/';
    }

    public function setPath($path)
    {
        $this->_path = rtrim($path, '/');
        $this->_pathStructure = null;
        $this->_idPath = null;
    }

    public function getPath()
    {
        return $this->_path;
    }


    public function getParentPath()
    {
        if (($path = dirname($this->_path)) === '.') {
            $path = '';
        }
        return $path;
    }


    public function getIdPath()
    {
        $this->getPathStructure();
        return $this->_idPath;
    }


    public function setIdPath($id)
    {
        $dir = models\GalDir::findOne($id);
        if ($dir !== null) {
            $this->setPath($dir->path);
            $this->getPathStructure();
        }
    }

    public function getFullStructure()
    {
        if ($this->_fullStructure === null) {
            $this->_fullStructure = models\Galpho::getCacheStructure($this->_idGroups);
        }
        return $this->_fullStructure;
    }

    public function resetFullStructure()
    {
        $this->_fullStructure = null;
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
                } else {
                    $this->_pathStructure = null;
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
        $this->_idGroups = is_array($idGroups) ? $idGroups : [$idGroups];
        $this->_pathStructure = null;
    }

    public function getPathInfo()
    {
        $this->getPathStructure();
        if (isset($this->_pathStructure['#'])) {
            return $this->_pathStructure['#'];
        }
        return [];
    }


    public function getImageInfo()
    {
        $this->getPathStructure();
        $elements = models\Galpho::getCacheListElementsForDir($this->_idPath, $this->_path);
        if (isset($elements[$this->_elementName])) {
            $element = $elements[$this->_elementName];
            return [
                'id' => $element['id'],
                'title' => $element['title'],
                'path' => $this->_elementBase . '/' . $this->_elementName,
                'description' => $element['description'],
                'info' => $element['info'],
                'createTime' => $element['createTime']
            ];
        }
        return false;
    }


    public function getListForFolder()
    {
        $this->getPathStructure();
        $list = [];
        if (is_array($this->_pathStructure)) {
            foreach ($this->_pathStructure as $key => $dir) {
                if ($key === '#') {
                    continue;
                }
                if (isset($dir['#'])) {
                    $list[] = $dir['#'] + ['type' => 'dir'];
                }
            }
        }
        if ($this->_idPath !== null) {
            $list = array_merge($list, models\Galpho::getCacheListElementsForDir($this->_idPath, $this->_path));
        }
        return $list;
    }


    public function addElement($filename, $title, $description)
    {

        try {
            $mime = FileHelper::getMimeType($filename);
            if ($mime == "image/jpeg") {
                $exif = new \app\galpho\Exif($filename);
            }
        } catch (Exception $e) {

        }

        $name = basename($filename);
        $dirId = $this->getIdPath();
        $element = models\GalElement::findOne(['dir_id' => $dirId, 'name' => $name]);
        if ($element === null) {
            $element = new models\GalElement();
            $element->name = $name;
            $element->dir_id = $dirId;
        }
        if ($title === null) {
            $title = $name;
        }
        $element->title = $title;
        $element->description = $description;
        $element->format = 'image';
        if (isset($exif)) {
            $tick = null;
            if (ctype_digit($exif->caption)) {
                $tick = strtotime($exif->caption);
                if ($tick) {
                    $element->created_at = $exif->caption;
                }

            }
            $element->info = json_encode([
                'caption' => $tick ? date('d.m.Y H:i', $tick) : '',
                'aperture' => $exif->aperture,
                'speed' => $exif->speed,
                'iso' => $exif->iso,
                'focal' => $exif->focal,
                'model' => $exif->model

            ]);
        }
        $element->save();
        // add image to parent folder if are not set
        foreach ($this->getParents() as $parent) {
            if ($parent['cover'] === null) {
                $galDir = models\GalDir::findOne($parent['id']);
                if ($galDir) {
                    $galDir->element_id_cover = $element->id;
                    $galDir->save();
                }
            } else {
                break;
            }

        }

    }

    public function addMoveElement($filename, $name, $description)
    {
        $exif = null;
        $dir = Yii::getAlias('@app/' . Yii::$app->params['image']['src']) . '/' . $this->getPath();

        if (!is_dir($dir)) {
            mkdir($dir, 0777, true);
        }
        $dst = $dir . '/' . $name;
        $out = @fopen($dst, "wb");
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
            $this->addElement($dst, $name, $description);

        }

    }

    public function getParents()
    {
        $this->getPathStructure();
        $list = [];
        $structure = $this->_fullStructure;
        foreach (explode('/', trim($this->_elementBase, '/')) as $key) {
            if (isset($structure['#'])) {
                $list[] = $structure['#'];
                $structure = & $structure[$key];
            }
        }
        $list[] = $this->getPathInfo();
        return array_reverse($list);
    }


    public function getBreadcrumb()
    {
        $list = [];
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

    /**
     * Clear all cache used by galpho ( image cache, sql cache)
     */
    public function clearCache()
    {
        // clear sql et yii cache
        models\Galpho::clearCache();
        // clear image cache
        FileHelper::removeDirectory(Yii::getAlias('@app/') . Yii::$app->params['image']['cache']);

        // clear right cache
        $dir = Yii::getAlias('@app/') . Yii::$app->params['image']['src'];
        if (!is_dir($dir)) {
            mkdir($dir, 0777, true);
        }
        $list = FileHelper::findFiles(Yii::getAlias('@app/') . Yii::$app->params['image']['src'], ['only' => ['right.php']]);
        foreach ($list as $file) {
            unlink($file);
        }
    }


    public function deleteImageCache($path)
    {
        $dir = Yii::getAlias('@app/' . Yii::$app->params['image']['cache']) . '/' . $path;
        foreach (Yii::$app->params['image']['format'] as $key => $value) {
            FileHelper::removeDirectory($dir . '/' . $key);
        }

    }


    public function addFolder($name, $title, $description)
    {
        $path = rtrim($this->getPath() . '/' . $name, '/');
        $dst = Yii::getAlias('@app/' . Yii::$app->params['image']['src'] . '/' . $path);
        if (!is_dir($dst)) {
            mkdir($dst, 0777, true);
        }

        $dir = models\GalDir::findOne(['path' => $path]);
        if ($dir === null) {
            $dir = new models\GalDir();
            $dir->path = $path;
        }
        $dir->title = $title;
        $dir->description = $description;
        $dir->save();
        $this->resetFullStructure();
        return $dir->id;
    }

    public function renameFolder($newName)
    {
        if ($this->_path === '' || $this->_path === $newName) {
            // root folder, we can't rename
            return false;
        }
        $dst = rtrim($this->getParentPath() . '/' . $newName, '/');
        // check if new folder doesn't exist
        if (models\Galpho::findPath($this->_fullStructure, $dst) !== false) {
            return false;
        }


        $dirSrc = Yii::getAlias('@app/' . Yii::$app->params['image']['src']) . '/' . $this->_path;
        $dirDst = Yii::getAlias('@app/' . Yii::$app->params['image']['src']) . '/' . $dst;
        @rename($dirSrc, $dirDst);
        models\GalDir::renameDir($this->_path, $dst);
        $this->deleteImageCache($this->path);
        $this->resetFullStructure();
        $this->setPath($dst);


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
//            $galDir->created_at = new \yii\db\Expression('NOW()');
//            $galDir->updated_at = new \yii\db\Expression('NOW()');

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


    public function getLanguages()
    {
        return Yii::$app->params['language'];
    }
}



