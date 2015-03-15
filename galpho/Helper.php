<?php
namespace app\galpho;

use Yii;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;
use  yii\base\Widget;
use yii\web\View;

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


    public static function dialog($url, $label = null, $header = null, $close = true, $options = [])
    {
        $id = self::getAutoId();
        $htmlClose = ($close) ? '<button type="button" class="close dialog-close" data-dismiss="modal" aria-label="Close" style="position:absolute;top:3px;right:3px"><span aria-hidden="true">&times;</span></button>' : '';
        $html = '<div id="' . $id . '" class="modal fade"><div class="modal-dialog"><div class="modal-content">';
        if ($header !== null) {
            $html .= '<div class="modal-header"><h2 class="modal-title" >' . $header . '</h2></div>';
        }
        $html .= '<div class="modal-body" ></div >'.$htmlClose.'</div ></div ></div >';
        $options['href'] = $url;
        $options ['data-modal'] = $id;
        $options['class'] = (isset($options['class']) ? $options['class'] : '') . 'glyphicon glyphicon-edit dialog-open';
        $view = Yii::$app->getView();
        $view->on(View::EVENT_END_BODY, function ($event) use ($html) {
            echo $html;
        });
        \yii\bootstrap\BootstrapPluginAsset::register($view);
        return Html::tag('a', $label, $options) . '&nbsp';
    }

    public static function pJax($linkSelector=null, $formSelector=null, $options=[]) {

        $id = self::getAutoId();
        $options = json_encode($options+['push'=>true,'replace'=>false,'timeout'=>1000,'scrollTo'=>0]);
        $linkSelector = json_encode($linkSelector !== null ? $linkSelector : '#' . $id . ' a [pjax]');
        $formSelector = json_encode($formSelector !== null ? $formSelector : '#' . $id . ' form');
        $view = Yii::$app->getView();

        \yii\widgets\PjaxAsset::register($view);
        $js = "jQuery(document).pjax($linkSelector, \"#$id\", $options);";
        $js .= "\njQuery(document).on('submit', $formSelector, function (event) {jQuery.pjax.submit(event, '#$id', $options);});";
        $view->registerJs($js);
        return $id;

    }

}