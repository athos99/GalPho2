<?php
namespace app\controllers\admin;

use Yii;
use yii\web\Controller;


class BaseController extends Controller {
    public function render($view, $params = []) {
        if (Yii::$app->request->isAjax) {
            return $this->renderAjax($view,$params);
        } else {
            return parent::render($view,$params);
        }
    }

}