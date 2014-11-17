<?php
namespace app\galpho;

use yii\helpers\ArrayHelper;
use yii\helpers\BaseHtml;
use yii\helpers\Url;

class Helper  {
    public static function editable($text, $params,  $title='', $options=[]) {
        $options['data-type']="text";
        $options['data-title']=$title;
        $options['data-pk']=ArrayHelper::getValue( $params, 'pk',0);
        unset($params['pk']);
        $options['data-url']=Url::to(['/admin/xedit']);
        $options['data-params'] = json_encode($params);



        $options['class']=(isset($options['class']) ? $options['class'].' ' : ''). 'galpho-editable';
        return  BaseHtml::tag('span', $text, $options);
    }
}