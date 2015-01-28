<?php
namespace app\galpho;

use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\Modal;

class Helper
{
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
        Modal::begin([
            'header' => 'Dialog',
            'toggleButton' => ['label' => $label, 'tag' => 'a', 'class' => $class, 'data-targetx' => Url::to($url)],
        ]);
        Modal::end();
        $view = Yii::$app->getView();
        $linkSelector = Json::encode('#' . $id . ' a');
        $formSelector = Json::encode('#' . $id . ' form[data-pjax]');
        PjaxAsset::register($view);

        $js = "jQuery(document).pjax($linkSelector, \"#$id\", $options);";
        $js .= "\njQuery(document).on('submit', $formSelector, function (event) {jQuery.pjax.submit(event, '#$id', $options);});";
        $view->registerJs($js);

    }
}