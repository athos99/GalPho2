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


    public static function dialog($url, $label = '', $class = null, $header = null)
    {
        if ($class === null) {
            $class = 'glyphicon glyphicon-edit';
        }
        $idButton = static::getAutoId();
        $idModal = static::getAutoId();
        echo Html::tag('a', $label, ['id' => $idButton, 'data-toggle' => 'modal', 'data-target' => '#'.$idModal, 'class' => $class]);
        echo Html::beginTag('div', ['id' => $idModal, 'class' => 'fade modal', 'role' => 'dialog']);
        echo Html::beginTag('div', ['class' => 'modal-dialog ']);
        echo Html::beginTag('div', ['class' => 'modal-content']);
        echo Html::beginTag('div', ['class' => 'modal-header']);
        echo Html::tag('button', '&times;', ['type' => 'button', 'class' => 'close', 'data-dismiss' => 'modal', 'aria-hidden' => 'true']);
        echo $header;
        echo Html::endTag('div');
        echo Html::tag('div', '&nbsp;$$$$$$', ['class' => 'modal-body']);
        echo Html::endTag('div');
        echo Html::endTag('div');
        echo Html::endTag('div');


        $view = Yii::$app->getView();
        $linkSelector = Json::encode('#' . $idModal . ' a');
        $formSelector = Json::encode('#' . $idModal . ' form[data-pjax]');

        \yii\bootstrap\BootstrapPluginAsset::register($view);
        \yii\widgets\PjaxAsset::register($view);
        $options = Json::encode(['push' => true, 'replace' => false, 'timeout' => 1000, 'scrollTo'=>false, 'url'=>$url]);
        $js = "jQuery(document).pjax($linkSelector, \"#$idModal\", $options);";
        $js .= "\njQuery(document).on('submit', $formSelector, function (event) {jQuery.pjax.submit(event, '#$idModal', $options);});";
        $options = Json::encode( ['url'=>$url,'container'=>'#'.$idModal,'fragment'=>'body','push'=>false]);
        $js .= "jQuery('#".$idButton."').on('click', function(event){\njQuery.pjax(".$options.");});";
        $view->registerJs($js);
    }
}