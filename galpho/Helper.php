<?php
namespace app\galpho;

use Yii;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\Json;
use yii\bootstrap\Modal;
use  yii\base\Widget;

class Helper
{
    public static function getAutoId()
    {
        return Widget::$autoIdPrefix . Widget::$counter++;
    }

    public static function editable($text, $params, $title = '', $options = [])
    {
        if (!isset($options['data-type'])) {
            $options['data-type'] = 'text';
        }
        $options['data-title'] = $title;
        $options['data-pk'] = ArrayHelper::getValue($params, 'pk', 0);
        unset($params['pk']);
        $options['data-url'] = Url::to(['/admin/xedit']);
        $options['data-params'] = json_encode($params);
        $options['class'] = (isset($options['class']) ? $options['class'] . ' ' : '') . 'galpho-editable';
        return Html::tag('span', $text, $options);
    }


    public static function dialog($url, $label = '', $header = null, $close = false, $options = array())
    {

        $id = self::getAutoId();
        echo '<div id="' . $id . '" class="modal fade"><div class="modal-dialog"><div class="modal-content">';
        if ($header !== null or $close) {
            echo '<div class="modal-header">';
            if ($close) {
                echo '<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>';
            }
            if ($header !== null) {
                echo '<h2 class="modal-title" >'.$header.'</h2>';
            }
            echo '</div>';
        }
        echo '<div class="modal-body" ></div ></div ></div ></div >';
        $options['href']= $url;
        $options ['data-modal']=$id;
        $options['class'] = (isset($options['class']) ?  $options['class'] : ''). 'glyphicon glyphicon-edit dialog-open';
        echo Html::tag('a', $label, $options);
        $view = Yii::$app->getView();
        \yii\bootstrap\BootstrapPluginAsset::register($view);
    }
}