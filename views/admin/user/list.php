<?php

use yii\helpers\Html;
use yii\grid\GridView;

/**
 * @var yii\base\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 */

$this->title = Yii::t('app/admin', 'Admin user');
$this->params['breadcrumbs'][] = $this->title;
?>
<h1><?= $this->title ?></h1>
<div class="gal-group-index">

    <p>
        <?php echo Html::a(Yii::t('app/admin', 'Create new user'), array('create'), array('class' => 'btn btn-danger')); ?>
    </p>

    <?php echo GridView::widget([
        'dataProvider' => $dataProvider,
//        'filterModel' => $model,
        'columns' => [
            'id:integer',
            'username:text',
            'email:email',
            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{view} {update} {delete} {group}',

                // delete button comportment, delete is possible if permanent is false
                'buttons' => ['delete' => function ($url, $model) {
                        return (empty($model->permanent)) ? Html::a('<span class="glyphicon glyphicon-trash"></span>', $url, [
                            'title' => Yii::t('yii', 'Delete'),
                            'data-confirm' => Yii::t('yii', 'Are you sure you want to delete this item?'),
                            'data-method' => 'post',
                            'data-pjax' => '0']) : '';
                    },

                    // user button comportment
                    'group' => function ($url, $model) {
                            return Html::a('<span class="glyphicon glyphicon-user"></span>', $url, [
                                'title' => Yii::t('app/admin', 'Manage group'),
                                'data-pjax' => '0']);
                        }],

            ],
        ]
    ]); ?>

</div>
