<?php
/**
 *
 *
 * Send a resized image file to the browser
 *
 * Usage :
 *   img.php/type/image_path
 *   img/type/image_path            with redirect url
 * or
 *   img.php?i=/type/image_path
 *
 *  Option
 *    ?download=1    for the user force a download image file from the brwoser in place of display image
 *
 *      exemple: http://x.x.x/library/img/t1/geneva/img_350.jpg
 *
 *      load the source image (from source image directory  appli/IMG_DIR/IMG_SCR/geneva/img_350.jpg)
 *      in function of the type of image to display
 *      - test if user can see the image in function of his right groupes appartenance
 *      - display a resized image or original image
 *      (in our example, resiize and dipslay a image of type T1 (thumbnail 1)
 *
 *  The system use system cache files for images resize and rights for best performances.
 *   the configuration of image types is in file application/config/img.php
 */
/*
  T thumbnail  Tx D0
  N normal     Nx D1
  R resize     Rx D2
  O original   O  D3
  E editor     E  D4
 *
 */
defined('YII_DEBUG') or define('YII_DEBUG', false);
defined('YII_ENV') or define('YII_ENV', 'prod');
defined('YII_ENV_DEV') or define('YII_ENV_DEV', false);

$config = require(__DIR__ . '/../config/web.php');


class Image
{
    public $config;
    public $format;
    public $db;


    public $img;
    public $path;
    public $srcFullName;
    public $dstFullName;
    public $srcDir;
    public $dstDir;
    public $idFormat = 0;

    public $width;
    public $height;
    public $crop = false;
    public $sharpen = 0;
    public $watermark = null;




    // the pos is based on numeric keyboard 7 8 9
    //                                      4 5 6
    //                                      1 2 3
    public $watermarkPos = 2;


    public $type = array(
        'jpg' => 'image/jpeg',
        'gif' => 'image/gif',
        'png' => 'image/pgn',
    );


    public function __construct($config, $db)
    {
        $this->config = $config;
        $this->db = $db;
        $this->getParam();
        if ($this->getRight()) {
            $this->getImage($this->srcFullName);

        } else {
            $this->getDefaultImage();
        }
        $this->output();

    }

    public function getParam()
    {
        $path = $this->resolvePathInfo();
        $params = explode('/', $path);

        array_shift($params); // img
        $this->idFormat = reset($params);
        if (isset($this->config['format'][$this->idFormat])) {
            $this->format = $this->config['format'][$this->idFormat];
            array_shift($params);
        } else {
            $this->format = $this->config['format'][0];
            $this->idFormat = 0;
        }
        $this->height = $this->format['height'];
        $this->width = $this->format['width'];

        if (!empty($this->format['crop'])) {
            $this->crop = true;
        }

        if (!empty($this->format['sharpen']) && is_numeric($this->format['sharpen'])) {
            $this->sharpen = $this->format['sharpen'];
        } else {
            $this->sharpen = 0;
        }
        $pathName = implode('/', $params);
        $this->srcFullName = __DIR__ . '/../' . $this->config['src'] . '/' . $pathName;
        $this->dstFullName = __DIR__ . '/../' . $this->config['cache'] . '/' . $this->idFormat . '/' . $pathName;
        $this->srcDir = dirname($this->srcFullName);
        $this->dstDir = dirname($this->dstFullName);
        $this->path = dirname($pathName);
        if ($this->path == '.') {
            $this->path = '';
        }
    }


    public function getRight()
    {
//    // get right access info
        $rightCacheFile = $this->srcDir . '/droit.php';
        $groupRights = [];
        if (file_exists($rightCacheFile) && ($Data = file_get_contents($rightCacheFile))) { // fetch from the cache file
            $groupRights = unserialize($Data);
        } else { // we need to get the access right from the DB
            $table_prefix = $this->db['tablePrefix'];
            $connection = new PDO($this->db['dsn'], $this->db['username'], $this->db['password']);

            $sql = "SELECT r.group_id,  r.value FROM {$table_prefix}gal_dir d
            LEFT JOIN {$table_prefix}gal_right r ON d.id = r.dir_id
            WHERE d.path=:path";
            $command = $connection->prepare($sql);
            $command->bindValue(':path', $this->path, PDO::PARAM_STR);
            $command->execute();

            $result = $command->fetchAll(PDO::FETCH_ASSOC);
            $groupRights = [];
            foreach ($result as $rec) {
                $groupRights[$rec['group_id']] = $rec['value'];
            }
            if (!is_dir(dirname($rightCacheFile))) {
                mkdir(dirname($rightCacheFile), 0777, TRUE);
            }
            file_put_contents($rightCacheFile, serialize($groupRights));
        }


        // check the access rights
        $bOk = FALSE;
        if (isset($groupRights[1]) && ($groupRights[1] & (1 << $this->idFormat))) {
            // special group anonymous
            $bOk = TRUE;
        } else {
            // read the user groups appartenances from the sessions var
            session_name('galsession');
            session_start();
            if (isset($_SESSION['GalGroups'])) {
                $groups = $_SESSION['GalGroups'];
            } else {
                $groups = [1];
            }
            session_write_close(); // close the session, the other process are not blocked
            // check the access
            foreach ($groups as $group) {
                if (isset($groupRights[$group]) && ($groupRights[$group] & (1 << $this->idFormat))) {
                    $bOk = TRUE;
                    break;
                }
            }
        }

        return $bOk;
    }

///*
//$connection->open();
//* $command = $connection->createCommand('SELECT * FROM post');
//* $posts = $command->queryAll();
//* $command = $connection->createCommand('UPDATE post SET status=1');
//* $command->execute();
//* ~~~
//*
//* One can also do prepared SQL execution and bind parameters to the prepared SQL.
//* When the parameters are coming from user input, you should use this approach
//* to prevent SQL injection attacks. The following is an example:
//*
//* ~~~
//* $command = $connection->createCommand('SELECT * FROM post WHERE id=:id');
//* $command->bindValue(':id', $_GET['id']);
//* $post = $command->query();
//* ~~~
//*
//* For more information about how to perform various DB queries, please refer to [[Command]].
//*
//* If the underlying DBMS supports transactions, you can perform transactional SQL queries
//* like the following:
//*
//* ~~~
//* $transaction = $connection->beginTransaction();
//* try {
//*     $connection->createCommand($sql1)->execute();
//*     $connection->createCommand($sql2)->execute();
//*     // ... executing other SQL statements ...
//*     $transaction->commit();
//* } catch (Exception $e) {
//    *     $transaction->rollBack();
//    * }
// * ~~~
// *
// * Connection is often used as an application component and configured in the application
//* configuration like the following:
// */


//    if (file_exists($rightcachefile) && ($Data = file_get_contents($rightcachefile))) {  // fetch from the cache file
//        $grouprights = unserialize($Data);
//    } else {  // we need to get the access right from the DB
//        $dbconfig = require 'application/config/database.php';
//        $conn = $dbconfig['default']['connection'];
//        $table_prefix = $dbconfig['default']['table_prefix'];
//
//        if ($mysqli = new mysqli($conn['hostname'], $conn['username'], $conn['password'], $conn['database'])) {
//            // get IdElem of the album
//            $sql = "SELECT IdElem FROM {$table_prefix}elements
//              WHERE Path = '{$mysqli->real_escape_string($subpath)}'";
//            if ($mysqli->real_query($sql)) {
//                if ($result = $mysqli->use_result()) {
//                    if ($row = $result->fetch_object()) {
//                        $idelem = $row->IdElem;
//                    }
//                    $result->close();
//                }
//            }
//            if (isset($idelem)) {
//                // get rights group  of the image
//                $sql = "SELECT IdGroup,D0,D1,D2,D3,D4,D5 FROM {$table_prefix}rights WHERE IdElem = {$idelem}";
//                if ($mysqli->real_query($sql)) {
//                    if ($result = $mysqli->use_result()) {
//                        while ($o = $result->fetch_object()) {
//                            $grouprights[$o->IdGroup] = array('D0' => $o->D0, 'D1' => $o->D1, 'D2' => $o->D2, 'D3' => $o->D3,
//                                'D4' => $o->D4, 'D5' => $o->D5);
//                        }
//                    }
//                    $result->close();
//                    if (!is_dir($src)) {
//                        mkdir($src, 0777, TRUE);
//                    }
//                    file_put_contents($rightcachefile, serialize($grouprights));
//                }
//            }
//        }
//    }
//
//    // check the access rights
//    $bOk = FALSE;
//    if (isset($grouprights[1]) && $grouprights[1][$config[$type]['right']]) {  // special group all user (anonymous & authentifed users)
//        $bOk = TRUE;
//    } else {
//        // read the user groups appartenances from the sessions var
//        session_name('galsession');
//        session_start();
//        if (isset($_SESSION['GalGroups'])) {
//            $Groups = $_SESSION['GalGroups'];
//        } else {
//            $Groups = array(1);
//        }
//        session_write_close();        // close the session, the other process are not blocked
//        // check the access
//        foreach ($Groups as $group) {
//            if (isset($grouprights[$group]) && $grouprights[$group][$config[$type]['right']]) {
//                $bOk = TRUE;
//                break;
//            }
//        }
//    }
//
//
//    if (!$bOk) {   // user is not allowed to dipslayed this image, display a black image
//        ErrImage($type);
//        return FALSE;
//    }
//


    /**
     * Output a image file to browser
     *
     * @param string $dst image file to output
     * @return boolean status
     */
    public function header($type)
    {
        if (!isset($this->type[$type])) {
            $type = 'jpg';
        }

        header("Content-type: " . $this->type[$type]);
        if (isset($_GET['download'])) { // option download
            header("Content-disposition: attachment; filename=" . basename($this->srcFullName));
        }
        $expires_offset = 86400; //1 day
        header('Expires: ' . gmdate("D, d M Y H:i:s", time() + $expires_offset) . ' GMT');
        header("Cache-Control: public, max-age=$expires_offset");
    }


    public function getDefaultImage()
    {
        $this->img = imagecreatetruecolor($this->width, $this->height);
        $backgroundColor = imagecolorallocate($this->img, 255, 255, 255);
        imagefill($this->img, 0, 0, $backgroundColor);
        $foregroundColor = imagecolorallocate($this->img, 255, 0, 0);
        imagepolygon($this->img, array(
            0, 0,
            $this->width - 1, 0,
            $this->width - 1, $this->height - 1,
            0, $this->height - 1,

        ), 4, $foregroundColor);
        imageline($this->img, 0, 0, $this->width - 1, $this->height - 1, $foregroundColor);
        imageline($this->img, $this->width - 1, 0, 0, $this->height - 1, $foregroundColor);
    }


    public function output()
    {
        if (!is_dir($this->dstDir)) {
            mkdir($this->dstDir, 777, true);
        }
        imagejpeg($this->img, $this->dstFullName);
        @chmod($this->dstFullName, 0777);
        imagedestroy($this->img);
        //    $this->header('jpg');
        flush();
        readfile($this->dstFullName);
    }


    /**
     * Returns the relative URL of the entry script.
     * The implementation of this method referenced Zend_Controller_Request_Http in Zend Framework.
     * @return string the relative URL of the entry script.
     * @throws InvalidConfigException if unable to determine the entry script URL
     */
    public function getScriptUrl()
    {
        $scriptFile = $_SERVER['SCRIPT_FILENAME'];
        $scriptName = basename($scriptFile);
        if (basename($_SERVER['SCRIPT_NAME']) === $scriptName) {
            $scriptUrl = $_SERVER['SCRIPT_NAME'];
        } elseif (basename($_SERVER['PHP_SELF']) === $scriptName) {
            $scriptUrl = $_SERVER['PHP_SELF'];
        } elseif (isset($_SERVER['ORIG_SCRIPT_NAME']) && basename($_SERVER['ORIG_SCRIPT_NAME']) === $scriptName) {
            $scriptUrl = $_SERVER['ORIG_SCRIPT_NAME'];
        } elseif (($pos = strpos($_SERVER['PHP_SELF'], '/' . $scriptName)) !== false) {
            $scriptUrl = substr($_SERVER['SCRIPT_NAME'], 0, $pos) . '/' . $scriptName;
        } elseif (isset($_SERVER['DOCUMENT_ROOT']) && strpos($scriptFile, $_SERVER['DOCUMENT_ROOT']) === 0) {
            $scriptUrl = str_replace('\\', '/', str_replace($_SERVER['DOCUMENT_ROOT'], '', $scriptFile));
        } else {
            throw new InvalidConfigException('Unable to determine the entry script URL.');
        }
        return $scriptUrl;
    }

    /**
     * Resolves the request URI portion for the currently requested URL.
     * This refers to the portion that is after the [[hostInfo]] part. It includes the [[queryString]] part if any.
     * The implementation of this method referenced Zend_Controller_Request_Http in Zend Framework.
     * @return string|boolean the request URI portion for the currently requested URL.
     * Note that the URI returned is URL-encoded.
     * @throws InvalidConfigException if the request URI cannot be determined due to unusual server configuration
     */
    public function resolveRequestUri()
    {
        if (isset($_SERVER['HTTP_X_REWRITE_URL'])) { // IIS
            $requestUri = $_SERVER['HTTP_X_REWRITE_URL'];
        } elseif (isset($_SERVER['REQUEST_URI'])) {
            $requestUri = $_SERVER['REQUEST_URI'];
            if ($requestUri !== '' && $requestUri[0] !== '/') {
                $requestUri = preg_replace('/^(http|https):\/\/[^\/]+/i', '', $requestUri);
            }
        } elseif (isset($_SERVER['ORIG_PATH_INFO'])) { // IIS 5.0 CGI
            $requestUri = $_SERVER['ORIG_PATH_INFO'];
            if (!empty($_SERVER['QUERY_STRING'])) {
                $requestUri .= '?' . $_SERVER['QUERY_STRING'];
            }
        }
        return $requestUri;
    }


    /**
     * Resolves the path info part of the currently requested URL.
     * A path info refers to the part that is after the entry script and before the question mark (query string).
     * The starting slashes are both removed (ending slashes will be kept).
     * @return string part of the request URL that is after the entry script and before the question mark.
     * Note, the returned path info is decoded.
     * @throws InvalidConfigException if the path info cannot be determined due to unexpected server configuration
     */
    protected function resolvePathInfo()
    {
        $pathInfo = $this->resolveRequestUri();

        if (($pos = strpos($pathInfo, '?')) !== false) {
            $pathInfo = substr($pathInfo, 0, $pos);
        }

        $pathInfo = urldecode($pathInfo);

        // try to encode in UTF8 if not so
        // http://w3.org/International/questions/qa-forms-utf-8.html
        if (!preg_match('%^(?:
				[\x09\x0A\x0D\x20-\x7E]              # ASCII
				| [\xC2-\xDF][\x80-\xBF]             # non-overlong 2-byte
				| \xE0[\xA0-\xBF][\x80-\xBF]         # excluding overlongs
				| [\xE1-\xEC\xEE\xEF][\x80-\xBF]{2}  # straight 3-byte
				| \xED[\x80-\x9F][\x80-\xBF]         # excluding surrogates
				| \xF0[\x90-\xBF][\x80-\xBF]{2}      # planes 1-3
				| [\xF1-\xF3][\x80-\xBF]{3}          # planes 4-15
				| \xF4[\x80-\x8F][\x80-\xBF]{2}      # plane 16
				)*$%xs', $pathInfo)
        ) {
            $pathInfo = utf8_encode($pathInfo);
        }

        $scriptUrl = $this->getScriptUrl();
        $baseUrl = rtrim(dirname($scriptUrl), '\\/');
        if (strpos($pathInfo, $scriptUrl) === 0) {
            $pathInfo = substr($pathInfo, strlen($scriptUrl));
        } elseif ($baseUrl === '' || strpos($pathInfo, $baseUrl) === 0) {
            $pathInfo = substr($pathInfo, strlen($baseUrl));
        } elseif (isset($_SERVER['PHP_SELF']) && strpos($_SERVER['PHP_SELF'], $scriptUrl) === 0) {
            $pathInfo = substr($_SERVER['PHP_SELF'], strlen($scriptUrl));
        } else {
            throw new Exception('Unable to determine the path info of the current request.');
        }

        return ltrim($pathInfo, '/');
    }


    function getImage($filename)
    {
        $this->img = null;

        $infoFile = pathinfo($filename);
        $imgType = strtolower($infoFile['extension']);

        switch ($imgType) {
            case 'jpg':
            case 'jpeg':
                $fctCreate = 'imagecreatefromjpeg';
                break;
            case 'gif':
                $fctCreate = 'imagecreatefromgif';
                break;
            case 'png':
                $fctCreate = 'imagecreatefrompng';
                break;
            default :
                return FALSE;
        }
        $img = @$fctCreate($filename);
        if (!$img) {
            return false;
        }
        $w1 = imagesx($img);
        $h1 = imagesy($img);
        $w2 = $this->width;
        $h2 = $this->height;
        $ox = 0;
        $oy = 0;
        $ratioW = $w2 / $w1;
        $ratioH = $h2 / $h1;
        $maxRatio = min($ratioW, $ratioH);
        if ($maxRatio > 1) {
            // can't resize image bigger than original
            $w2 = $w1;
            $h2 = $h1;
            $ratioW = 1;
            $ratioH = 1;
        }
        if ($this->crop == FALSE) {
            // resize without crop
            if ($ratioW < $ratioH) {
                $h2 = (int)round($ratioW * $h1);
            } else {
                $w2 = (int)round($ratioH * $w1);
            }
        } else {
            // crop
            if ($ratioW < $ratioH) {
                $w1a = (int)round($w2 / $ratioH);
                $ox = (int)round(($w1 - $w1a) / 2);
                $w1 = $w1a;
            } else {
                $h1a = (int)round($h2 / $ratioW);
                $oy = (int)round(($h1 - $h1a) / 2);
                $h1 = $h1a;
            }
        }
        $this->img = imagecreatetruecolor($w2, $h2);
        imagecopyresampled($this->img, $img, 0, 0, $ox, $oy, $w2, $h2, $w1, $h1);
        imagedestroy($img);
        if (($this->sharpen != 0) && ($maxRatio < .8)) {
            $amount = round(abs(-28 + ($this->sharpen * 0.16)), 2);
            // Gaussian blur matrix
            $matrix = array(
                array(-1, -1, -1),
                array(-1, $amount, -1),
                array(-1, -1, -1));
            // Perform the sharpen
            imageconvolution($this->img, $matrix, $amount - 8, 0);
        }
        if ($this->watermark) {
            if (file_exists('image/' . $this->watermark)) {
                $waterFile = pathinfo('image/' . $this->watermark);
                switch (strtolower($waterFile['extension'])) {
                    case 'jpg':
                    case 'jpeg':
                        $fctcreate = 'imagecreatefromjpeg';
                        break;
                    case 'gif':
                        $fctcreate = 'imagecreatefromgif';
                        break;
                    case 'png':
                        $fctcreate = 'imagecreatefrompng';
                        break;
                    default :
                        $fctcreate = NULL;
                }
                if ($fctcreate) {
                    $img = @$fctcreate('image/' . $this->watermark);
                    if ($img) {
                        if (isset($this->watermarkPos)) {
                            $pos = $this->watermarkPos;
                        } else {
                            $pos = 2;
                        }
                        $w1 = imagesx($img);
                        $h1 = imagesy($img);

                        // the pos is based on numeric keyboard 7 8 9
                        //                                      4 5 6
                        //                                      1 2 3
                        switch ($pos) {
                            case 1:
                                $ox = 0;
                                $oy = $h2 - $h1;
                                break;
                            case 2:
                                $ox = (int)round(($w2 - $w1) / 2);
                                $oy = $h2 - $h1;
                                break;
                            case 3:
                                $ox = $w2 - $w1;
                                $oy = $h2 - $h1;
                                break;
                            case 4:
                                $ox = 0;
                                $oy = (int)round(($h2 - $h1) / 2);
                                break;
                            case 5:
                                $ox = (int)round(($w2 - $w1) / 2);
                                $oy = (int)round(($h2 - $h1) / 2);
                                break;
                            case 6:
                                $ox = $w2 - $w1;
                                $oy = (int)round(($h2 - $h1) / 2);
                                break;
                            case 7:
                                $ox = $oy = 0;
                                break;
                            case 8:
                                $ox = (int)round(($w2 - $w1) / 2);
                                $oy = 0;
                                break;
                            case 9:
                                $ox = $w2 - $w1;
                                $oy = 0;
                                break;
                            default:
                                $ox = (int)round(($w2 - $w1) / 2);
                                $oy = $h2 - $h1;
                                break;
                        }

                        $ox = max(0, $ox);
                        $oy = max(0, $oy);
                        imagecopy($this->img, $img, $ox, $oy, 0, 0, $w1, $h1);
                        imagedestroy($img);
                    }
                }
            }
        }

    }


}

new Image($config['params']['image'], $config['components']['db']);







//
///**
// *
// *
// * Send a resized image file to the browser
// *
// * Usage :
// *   img.php/type/image_path
// *   img/type/image_path            with redirect url
// * or
// *   img.php?i=/type/image_path
// *
// *  Option
// *    ?download=1    for the user force a download image file from the brwoser in place of display image
// *
// *      exemple: http://x.x.x/library/img/t1/geneva/img_350.jpg
// *
// *      load the source image (from source image directory  appli/IMG_DIR/IMG_SCR/geneva/img_350.jpg)
// *      in function of the type of image to display
// *      - test if user can see the image in function of his right groupes appartenance
// *      - display a resized image or original image
// *      (in our example, resiize and dipslay a image of type T1 (thumbnail 1)
// *
// *  The system use system cache files for images resize and rights for best performances.
// *   the configuration of image types is in file pplication/config/img.php
// */
///*
//  T thumbnail  Tx D0
//  N normal     Nx D1
//  R resize     Rx D2
//  O original   O  D3
//  E editor     E  D4
// *
// */
//
//
//
//if (!defined('SYSPATH')) {
//    define('SYSPATH', 'application/system');
//}
//if (!defined('DOCROOT')) {
//    $abspath = pathinfo(__FILE__);
//    $absdir = $abspath ['dirname'] . '/';
//    define('DOCROOT', $absdir);
//}
//
//$data = require 'application/config/img.php';
//$config = &$data['config'];
//
//$config[IMG_SRC_DIR] = array('right' => 'D3', 'class' => 'O', 'x' => 600, 'y' => 600);
//
///**
// * Output a image file to browser
// *
// * @param string $dst image file to output
// * @return boolean status
// */
//function OutputImage($dst) {
//    $dstinfo = pathinfo($dst);
//    $imgtype = strtolower($dstinfo['extension']);
//    switch ($imgtype) {
//        case 'jpg':
//        case 'jpeg':
//            $header = 'image/jpeg';
//            break;
//        case 'gif':
//            $header = 'image/gif';
//            break;
//        case 'png':
//            $header = 'image/png';
//            break;
//        default :
//            return FALSE;
//    }
//    header("Content-type: " . $header);
//    if (isset($_GET['download'])) {   // option download
//        header("Content-disposition: attachment; filename=" . $dstinfo['basename']);
//    }
//    $expires_offset = 86400; //1 day
//    header('Expires: ' . gmdate("D, d M Y H:i:s", time() + $expires_offset) . ' GMT');
//    header("Cache-Control: public, max-age=$expires_offset");
//    flush();
//    readfile($dst);
//    return true;
//}
//
///**
// * If not exist create a file image (black image) and output to the browser
// * @param string $type image type
// * @return TRUE
// */
//function ErrImage($type) {
//    global $config;
//    $abspath = pathinfo(__FILE__);
//    $FileName = $abspath['dirname'] . '/' . IMG_DIR . '/' . $type . '.jpg';
//    if (!file_exists($FileName)) {
//        $img = imagecreatetruecolor($config[$type]['x'], $config[$type]['y']);
//        $fond = imagecolorallocate($img, 0, 0, 0);
//        imagefill($img, 0, 0, $fond);
//        imagejpeg($img, $FileName);
//        imagedestroy($img);
//    }
//    header("Content-type: image/jpeg");
//    flush();
//    readfile($FileName);
//    return TRUE;
//}
//
///**
// * Resize image and store in a file
// *
// * @param string $dst destination file
// * @param string $src source image file
// * @param string $type image type to create
// * @param boolean $force_jpg force to create a jpg image
// * @return boolean status
// */
//function CreateImage($dst, $src, $info, $force_jpg=FALSE) {
//    global $config;
//
//    $infodest = pathinfo($dst);
//    $dirdst = $infodest['dirname'];
//    $imgtype = strtolower($infodest['extension']);
//    if (!is_dir($dirdst)) {
//        mkdir($dirdst, 0777, TRUE);
//    }
//    switch ($imgtype) {
//        case 'jpg':
//        case 'jpeg':
//            $fctcreate = 'imagecreatefromjpeg';
//            $fctsave = 'imagejpeg';
//            break;
//        case 'gif':
//            $fctcreate = 'imagecreatefromgif';
//            $fctsave = 'imagegif';
//            break;
//        case 'png':
//            $fctcreate = 'imagecreatefrompng';
//            $fctsave = 'imagepng';
//            break;
//        default :
//            return FALSE;
//    }
//    if ($force_jpg) {
//        $fctsave = 'imagejpeg';
//    }
//    $img = @$fctcreate($src);
//    if (!$img) {
//        return false;
//    }
//    $w1 = imagesx($img);
//    $h1 = imagesy($img);
//    $w2 = $info['x'];
//    $h2 = $info['y'];
//    $ox = 0;
//    $oy = 0;
//    $ratiow = $w2 / $w1;
//    $ratioh = $h2 / $h1;
//    $maxratio = min($ratiow, $ratioh);
//    if ($maxratio > 1) {
//        // can't resize image bigger than original
//        $w2 = $w1;
//        $h2 = $h1;
//        $ratiow = 1;
//        $ratioh = 1;
//    }
//    if ($info['crop'] == FALSE) {
//        // resize without crop
//        if ($ratiow < $ratioh) {
//            $h2 = (int) round($ratiow * $h1);
//        } else {
//            $w2 = (int) round($ratioh * $w1);
//        }
//    } else {
//        // crop
//        if ($ratiow < $ratioh) {
//            $w1a = (int) round($w2 / $ratioh);
//            $ox = (int) round(($w1 - $w1a) / 2);
//            $w1 = $w1a;
//        } else {
//            $h1a = (int) round($h2 / $ratiow);
//            $oy = (int) round(($h1 - $h1a) / 2);
//            $h1 = $h1a;
//        }
//    }
//    $img2 = imagecreatetruecolor($w2, $h2);
//    imagecopyresampled($img2, $img, 0, 0, $ox, $oy, $w2, $h2, $w1, $h1);
//    imagedestroy($img);
//    if (($info['sharpen'] > 0) && ($maxratio < .8)) {
//        // Amount should be in the range of 18-10
////    $amount = round(abs(-18 + ($info['sharpen'] * 0.08)), 2);
//        $amount = round(abs(-28 + ($info['sharpen'] * 0.16)), 2);
//        // Gaussian blur matrix
//        $matrix = array(
//            array(-1, -1, -1),
//            array(-1, $amount, -1),
//            array(-1, -1, -1));
//        // Perform the sharpen
//        imageconvolution($img2, $matrix, $amount - 8, 0);
//    }
//    if ($info['watermark']) {
//        if (file_exists('image/' . $info['watermark'])) {
//            $wfile = pathinfo('image/' . $info['watermark']);
//            switch (strtolower($wfile['extension'])) {
//                case 'jpg':
//                case 'jpeg':
//                    $fctcreate = 'imagecreatefromjpeg';
//                    break;
//                case 'gif':
//                    $fctcreate = 'imagecreatefromgif';
//                    break;
//                case 'png':
//                    $fctcreate = 'imagecreatefrompng';
//                    break;
//                default :
//                    $fctcreate = NULL;
//            }
//            if ($fctcreate) {
//                $img = @$fctcreate('image/' . $info['watermark']);
//                if ($img) {
//                    if (isset($info['watermarkpos'])) {
//                        $pos = $info['watermarkpos'];
//                    } else {
//                        $pos = 2;
//                    }
//                    $w1 = imagesx($img);
//                    $h1 = imagesy($img);
//
//                    // the pos is based on numeric keyboard 7 8 9
//                    //                                      4 5 6
//                    //                                      1 2 3
//                    switch ($pos) {
//                        case 1:
//                            $ox = 0;
//                            $oy = $h2 - $h1;
//                            break;
//                        case 2:
//                            $ox = (int) round(($w2 - $w1) / 2);
//                            $oy = $h2 - $h1;
//                            break;
//                        case 3:
//                            $ox = $w2 - $w1;
//                            $oy = $h2 - $h1;
//                            break;
//                        case 4:
//                            $ox = 0;
//                            $oy = (int) round(($h2 - $h1) / 2);
//                            break;
//                        case 5:
//                            $ox = (int) round(($w2 - $w1) / 2);
//                            $oy = (int) round(($h2 - $h1) / 2);
//                            break;
//                        case 6:
//                            $ox = $w2 - $w1;
//                            $oy = (int) round(($h2 - $h1) / 2);
//                            break;
//                        case 7:
//                            $ox = $oy = 0;
//                            break;
//                        case 8:
//                            $ox = (int) round(($w2 - $w1) / 2);
//                            $oy = 0;
//                            break;
//                        case 9:
//                            $ox = $w2 - $w1;
//                            $oy = 0;
//                            break;
//                        default:
//                            $ox = (int) round(($w2 - $w1) / 2);
//                            $oy = $h2 - $h1;
//                            break;
//                    }
//
//                    $ox = max(0, $ox);
//                    $oy = max(0, $oy);
//                    imagecopy($img2, $img, $ox, $oy, 0, 0, $w1, $h1);
//                    imagedestroy($img);
//                }
//            }
//        }
//    }
//    if ($info['quality'] > 0) {
//        $fctsave($img2, $dst, $info['quality']);
//    } else {
//        $fctsave($img2, $dst);
//    }
//    @chmod($dst,0777);
//    imagedestroy($img2);
//}
//
///**
// * - Check the access of the image
// * - Display the image
// *
// *
// * @return boolean status
// */
//function GetImage() {
//    global $config;
//
//    // image paths
//    $absdir = DOCROOT . IMG_DIR;
//
//    // get parameters of image to display
//    if (isset($_SERVER['PATH_INFO']) AND $_SERVER['PATH_INFO']) { // for img.php/type/image_path
//        $pathinfo = $_SERVER['PATH_INFO'];
//    } elseif (isset($_GET['i'])) {  // for img.php?i=/type/image_path
//        $pathinfo = '/' . $_GET['i'];
//    } else {
//        echo 'no parameter';
//        return FALSE;
//    }
//
//    $dst = $absdir . $pathinfo;
//
//    // read parameters
//    $subpath = explode('/', $pathinfo);
//    if ($subpath[0] == '') {
//        array_shift($subpath);
//    }
//    $type = array_shift($subpath);    // image type
//    if (!isset($config[$type])) {
//        echo 'unknow type';
//        return FALSE;
//    }
//    $imgfilename = array_pop($subpath);     // image file_name
//    $subpath = implode('/', $subpath); // image path
//    // source image directory
//    if (empty($subpath)) {
//        $src = $absdir . '/' . IMG_SRC_DIR;
//    } else {
//        $subpath = '/' . $subpath;
//        $src = $absdir . '/' . IMG_SRC_DIR . $subpath;
//    }
//
//
//    // get right access info
//    $rightcachefile = $src . '/droit.php';
//    $grouprights = array();
//    if (file_exists($rightcachefile) && ($Data = file_get_contents($rightcachefile))) {  // fetch from the cache file
//        $grouprights = unserialize($Data);
//    } else {  // we need to get the access right from the DB
//        $dbconfig = require 'application/config/database.php';
//        $conn = $dbconfig['default']['connection'];
//        $table_prefix = $dbconfig['default']['table_prefix'];
//
//        if ($mysqli = new mysqli($conn['hostname'], $conn['username'], $conn['password'], $conn['database'])) {
//            // get IdElem of the album
//            $sql = "SELECT IdElem FROM {$table_prefix}elements
//              WHERE Path = '{$mysqli->real_escape_string($subpath)}'";
//            if ($mysqli->real_query($sql)) {
//                if ($result = $mysqli->use_result()) {
//                    if ($row = $result->fetch_object()) {
//                        $idelem = $row->IdElem;
//                    }
//                    $result->close();
//                }
//            }
//            if (isset($idelem)) {
//                // get rights group  of the image
//                $sql = "SELECT IdGroup,D0,D1,D2,D3,D4,D5 FROM {$table_prefix}rights WHERE IdElem = {$idelem}";
//                if ($mysqli->real_query($sql)) {
//                    if ($result = $mysqli->use_result()) {
//                        while ($o = $result->fetch_object()) {
//                            $grouprights[$o->IdGroup] = array('D0' => $o->D0, 'D1' => $o->D1, 'D2' => $o->D2, 'D3' => $o->D3,
//                                'D4' => $o->D4, 'D5' => $o->D5);
//                        }
//                    }
//                    $result->close();
//                    if (!is_dir($src)) {
//                        mkdir($src, 0777, TRUE);
//                    }
//                    file_put_contents($rightcachefile, serialize($grouprights));
//                }
//            }
//        }
//    }
//
//    // check the access rights
//    $bOk = FALSE;
//    if (isset($grouprights[1]) && $grouprights[1][$config[$type]['right']]) {  // special group all user (anonymous & authentifed users)
//        $bOk = TRUE;
//    } else {
//        // read the user groups appartenances from the sessions var
//        session_name('galsession');
//        session_start();
//        if (isset($_SESSION['GalGroups'])) {
//            $Groups = $_SESSION['GalGroups'];
//        } else {
//            $Groups = array(1);
//        }
//        session_write_close();        // close the session, the other process are not blocked
//        // check the access
//        foreach ($Groups as $group) {
//            if (isset($grouprights[$group]) && $grouprights[$group][$config[$type]['right']]) {
//                $bOk = TRUE;
//                break;
//            }
//        }
//    }
//
//
//    if (!$bOk) {   // user is not allowed to dipslayed this image, display a black image
//        ErrImage($type);
//        return FALSE;
//    }
//
//    if (is_file($dst)) {    // check if the image to diplay is in the cache
//        OutputImage($dst);
//        return TRUE;
//    } else {
//        if (!is_file($src . '/' . $imgfilename) or empty($imgfilename)) {  // check if the source file image exist
//            $p = pathinfo($imgfilename);
//            $imgfilename = strtoupper($p['filename']) . '.' . strtolower($p['extension']);
//            if (!is_file($src . '/' . $imgfilename) or empty($imgfilename)) {  // check if the source file image exist
//                file_put_contents(DOCROOT . 'img.log', $src . '/' . $imgfilename . " not found\n", FILE_APPEND);
//                ErrImage($type);
//                return FALSE;
//            }
//        } else {
//            $src .= '/' . $imgfilename;
//            CreateImage($dst, $src, $config[$type], FALSE);
//            OutputImage($dst);
//        }
//        return TRUE;
//    }
//}
//
//if (!defined('FILE2INCLUDE')) {   // FILE2INCLUDE is make for include this file and doesn't execute getimage
//    GetImage();
//}
//
