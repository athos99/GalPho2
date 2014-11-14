<?php
use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use app\assets\AppAsset;
use app\widgets\Alert;

/**
 * @var \yii\web\View $this
 * @var string $content
 */
AppAsset::register($this);


/** @var \app\galpho\Galpho $galpho */
$galpho = Yii::$app->get('galpho');

?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>"/>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>
<div class="wrap">
    <?php
    NavBar::begin([
        'brandLabel' => 'My Company',
        'brandUrl' => Yii::$app->homeUrl,
        'options' => [
            'class' => 'navbar-inverse navbar-fixed-top',
        ],
    ]);
    $menuItems = [
        ['label' => 'Home', 'url' => ['/site/index']],
        ['label' => 'Galery', 'url' => ['/v']],
        ['label' => 'About', 'url' => ['/site/about']],
        ['label' => 'Contact', 'url' => ['/site/contact']],
        ['label' => 'Folder', 'items' =>
            [
                ['label' => 'Create sub folder', 'url' => ['/admin/folder/create', 'id' => $galpho->getIdPath()]],
                ['label' => 'Edit', 'url' => ['/admin/folder/edit', 'id' => $galpho->getIdPath()]],
                ['label' => 'Right', 'url' => ['/admin/folder/right', 'id' => $galpho->getIdPath()]],
            ],
        ],
        ['label' => 'Dropdown', 'items' =>
            [
                ['label' => 'Clear cache', 'url' => ['/admin/cache/clear']],
                ['label' => 'Add images', 'url' => ['/admin/folder/add', 'id' => $galpho->getIdPath()]],
                ['label' => 'User', 'url' => ['/admin/user']],
                ['label' => 'Group', 'url' => ['/admin/group']],

                '<li class="divider"></li>',
                '<li class="dropdown-header">Dropdown Header</li>',
                ['label' => 'Level 1 - Dropdown B', 'url' => '#'],
            ],
        ],

    ];

    $menuItems[] =

        Html::beginTag('form', ['method' => 'get', 'class' => 'navbar-form navbar-left']) .
//        Html::beginForm('','get',['class'=>'navbar-form navbar-left']) .
        Html::input('text', 'search', \yii\helpers\ArrayHelper::getValue($_GET, 'search', ''), ['placeholder' => Yii::t('app/admin', 'Search'), 'class' => 'form-control']) .
        Html::submitButton(Yii::t('app/admin', 'Submit'), ['class' => 'btn btn-default']) .
//        Html::endForm();
        Html::endTag('form');


    if (Yii::$app->user->isGuest) {
        $menuItems[] = ['label' => 'Signup', 'url' => ['/site/signup']];
        $menuItems[] = ['label' => 'Login', 'url' => ['/site/login']];
    } else {
        $menuItems[] = [
            'label' => 'Logout (' . Yii::$app->user->identity->username . ')',
            'url' => ['/site/logout'],
            'linkOptions' => ['data-method' => 'post']
        ];
    }
    echo Nav::widget([
        'options' => ['class' => 'navbar-nav navbar-right'],
        'items' => $menuItems,
    ]);
    NavBar::end();
    ?>

    <div class="container">

        <span data-name="title"
              data-type="text"
              data-params="{table:'folder',id:'1', field:'title'}"
              data-url="<?=\yii\helpers\Url::to(['/admin/folder/edit'])?>"
              data-pk="1"
              data-title="Enter username" class="galpho-editable"
              data-original-title=""
              title="">span</span>
        <a href="#" data-type="text" data-pk="1" data-title="Enter username" class="galpho-editable" data-original-title="" title="">superuser</a>

        <?=
        Breadcrumbs::widget([
            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
        ]) ?>
        <?= Alert::widget() ?>
        <?= $content ?>
    </div>
</div>

<footer class="footer">
    <div class="container">
        <p class="pull-left">&copy; My Company <?= date('Y') ?></p>

        <p class="pull-right"><?= Yii::powered() ?></p>
    </div>
</footer>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
