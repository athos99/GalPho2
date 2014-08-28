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
    <p>
        <?php echo Html::a(Yii::t('app/admin', 'Create new group'), array('create'), array('class' => 'btn btn-danger')); ?>
    </p>
    <?php echo GridView::widget([
        'dataProvider' => $dataProvider,

        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'id',
            'permanent:boolean',
            'name',
            'description:text',
            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{view} {update} {delete} {user} {right}',

                // delete button comportment, delete is possible if permanent is false
                'buttons' => [
                    'delete' => function ($url, $model) {
                            return (empty($model->permanent)) ? Html::a('<span class="glyphicon glyphicon-trash"></span>', $url, [
                                'title' => Yii::t('yii', 'Delete'),
                                'data-confirm' => Yii::t('yii', 'Are you sure you want to delete this item?'),
                                'data-method' => 'post',
                                'data-pjax' => '0']) : '';
                        },

                    // user button comportment
                    'user' => function ($url, $model) {
                            return (empty($model->permanent)) ? Html::a('<span class="glyphicon glyphicon-user"></span>', $url, [
                                'title' => Yii::t('app/admin', 'Manage user'),
                                'data-pjax' => '0']) : '';
                        },

                    // right folder button comportment
                    'right' => function ($url, $model) {
                            return Html::a('<span class="glyphicon glyphicon-folder-open"></span>', $url, [
                                'title' => Yii::t('app/admin', 'Manage rights'),
                                'data-pjax' => '0']);
                        }
                ],
            ],
        ]
    ]); ?>
</div>
