<?php

class Editable extends \yii\helpers\BaseHtml {
    public static function editable($text, $params, $options) {
        return static::tag('a', $text, $options);
    }
}