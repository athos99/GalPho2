<?php
namespace app\controllers;

use Yii;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\helpers\Html;


class VController extends Controller
{
    public function actionIndex()
    {
        $path = ArrayHelper::getValue($_GET, 'path', '');

        /** @var \app\galpho\Galpho $galpho */
        $galpho = Yii::$app->get('galpho');
        $galpho->setPath($path);
        $galpho->setGroups([1, 2, 3]);
        if ( $pathStructure = $galpho->getPathStructure() === false) {
            Yii::$app->getSession()->setFlash('error', 'The path '. $path.' doesn\'t exist');
            return Yii::$app->getResponse()->redirect($galpho->url);
        }

        if (isset($_POST['file']) and is_array($_POST['file'])) {
            /** @var \athos99\plupload\PluploadManager $uploadManager */
            $uploadManager = Yii::$app->get('uploadManager');
            foreach ($_POST['file'] as $file) {
                $file = $uploadManager->cleanFileName($file);
                if ($file != '') {
                    $filename = $uploadManager->targetDir.'/'.$file;
                    $galpho->addMoveElement($filename, $file,null);


                }
            }
            return Yii::$app->getResponse()->redirect(Yii::$app->getRequest()->url);

        }


//        $galpho->repair();
        switch ($galpho->getViewMode()) {
            case \app\galpho\Galpho::VIEW_LIST :
                return $this->render('//site/list', ['galpho' => $galpho]);
                break;
            case \app\galpho\Galpho::VIEW_DETAIL :
                return $this->render('//site/detail', ['galpho' => $galpho]);
                break;
            default :
                Yii::$app->getResponse()->redirect(['/']);
        }

    }
}
