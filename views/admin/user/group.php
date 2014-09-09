<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\assets\ListorderAsset;

/**
 * @var yii\web\View $this
 */


ListorderAsset::register($this);
$groupList = \yii\helpers\ArrayHelper::map($groups, 'id', 'name');
$selGroupList = \yii\helpers\ArrayHelper::map($selGroups, 'group_id', 'group.name');

$this->title = Yii::t('app/admin', 'Admin user of {user}', ['user' =>$user->username]);
$this->params['breadcrumbs'][] = $this->title;
?>
    <h1><?= $this->title ?></h1>

<?php
/*  @var yii\widgets\ActiveForm $form */
$form = ActiveForm::begin(); ?>

    <div class="row">
        <div class="col-xs-12">
            <div class="row">
                <div class="col-xs-6">
                    <div class="form-group">
                        <label for="galUserGroups">Groups of user</label>
                        <?=
                        Html::dropDownList('galUserGroups', null, $selGroupList, ['size' => 10, 'multiple' => 'multiple', 'id' => 'galuser-galusergroups', 'class' => 'form-control']);
                        ?>
                    </div>
                </div>
                <div class="col-xs-6">
                    <div class="form-group">
                        <label for="galgroups">Groups</label>
                        <?= Html::dropDownList('galgroups', null, $groupList, ['size' => 10, 'multiple' => 'multiple', 'id' => 'galgroups', 'class' => 'form-control']); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row form-horizontal">
        <div class="form-group">
            <label for="filter1" class="col-xs-2 control-label">Filter</label>

            <div class="col-xs-10">
                <input id="filter1" class="form-control " type="text" placeholder="search....">
            </div>
        </div>
    </div>
    <div class="row">
        <div class="form-group">
            <?= Html::resetButton(Yii::t('app/admin', 'Reset'), ['class' => 'btn btn-default']); ?>
            <?= Html::submitButton(Yii::t('app/admin', 'Cancel'), ['class' => 'btn btn-default  no-validation', 'name' => 'cancel']) ?>
            <?= Html::submitButton(Yii::t('app/admin', 'Save'), ['class' => 'btn btn-primary', 'name' => 'save']) ?>
        </div>
    </div>
<?php ActiveForm::end(); ?>

    </div><!-- form -->
<?php
$this->registerJs(<<<EOT
    $('#galuser-galusergroups').listorder({submitAll: true});
    $('#galgroups').listorder();
    $('#galuser-galusergroups').listorder('duoExclude', $('#galgroups'));
    $('#filter1').on('keyup', function () {
        $('#galuser-galusergroups').listorder('filter', $(this).val());
        $('#galgroupss').listorder('filter', $(this).val());
    });
EOT
);


