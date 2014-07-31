<?php

use yii\helpers\Html;
use yii\grid\GridView;

/**
 * @var yii\base\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var app\models\GalGroupSearch $searchModel
 */

$this->title = 'Gal Groups';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="gal-group-index">

    <h1><?php echo Html::encode($this->title); ?></h1>

    <?php  echo $this->render('_searchForm', ['model' => $model]); ?>

    <p>
        <?php echo Html::a('Create GalGroup', array('create'), array('class' => 'btn btn-danger')); ?>
    </p>

    <?php echo GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $model,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'id',
            'permanent:boolean',
            'name',
            'description:ntext',
            [
                'class' => 'yii\grid\ActionColumn',

             // delete button comportment, delete is possible if permanent is false
                'buttons'=>['delete' => function ($url, $model) {
                    return (empty($model->permanent)) ? Html::a('<span class="glyphicon glyphicon-trash"></span>', $url, [
                        'title' => Yii::t('yii', 'Delete'),
                        'data-confirm' => Yii::t('yii', 'Are you sure you want to delete this item?'),
                        'data-method' => 'post',
                        'data-pjax' => '0']) : '';
                }]
            ],
        ]
    ]); ?>

</div>
