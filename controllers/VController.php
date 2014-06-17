<?php
namespace app\controllers;
use Yii;
use yii\helpers\ArrayHelper;
use yii\web\Controller;


class VController extends Controller
{
    public function actionIndex()
    {
        $path = ArrayHelper::getValue($_GET, 'path', '/');

        /** @var \app\galpho\Galpho $galpho */
        $galpho = Yii::$app->get('galpho');
        $galpho->setPath($path);
        $galpho->setGroups(array(1, 2, 3));

        if (isset($_POST['file']) and is_array($_POST['file'])) {
            foreach($_POST['file'] as $file) {
                /** @var \athos99\plupload\PluploadManager $uploadManager*/
                $uploadManager = Yii::$app->get('uploadManager') ;
                if ( $file != '') {
                    $filename = $uploadManager->getFilename($file);
                    $galpho->addElement($filename, $file);


                }
            }

        }



//        $galpho->repair();
        switch ($galpho->getViewMode()) {
            case \app\galpho\Galpho::VIEW_LIST :
                echo $this->render('//site/list', array('galpho' => $galpho));
                break;
            case \app\galpho\Galpho::VIEW_DETAIL :
                echo $this->render('//site/detail', array('galpho' => $galpho));
                break;
            default :
                Yii::$app->getResponse()->redirect(array('/'));
        }

    }
}
