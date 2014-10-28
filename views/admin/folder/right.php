<?php
use yii\helpers\Html;
use yii\grid\GridView;

/**
 * @var yii\base\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var app\models\GalGroupSearch $searchModel
 */

$this->title = Yii::t('app/admin', 'Admin group');
$this->params['breadcrumbs'][] = $this->title;
?>
<h1><?= $this->title ?></h1>

<div class="gal-group-index">
    <?php

    $headerhtml = '';
    foreach (Yii::$app->params['right'] as $i => $name) {
        $headerhtml .= '<span>' . $name . '</span>';
    }

    echo GridView::widget([
        'dataProvider' => $dataProvider,

        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'group.name',
            'group.description:text',
            [
                'class' => 'yii\grid\DataColumn',
                'header' => $headerhtml,
                'value' => function ($data) {
                        return 'xx';
                    }
            ]
            ,
        ]
    ]); ?>
</div>
