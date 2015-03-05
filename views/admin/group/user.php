<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\assets\ListorderAsset;

/**
 * @var yii\web\View $this
 */


ListorderAsset::register($this);
$userList = \yii\helpers\ArrayHelper::map($users, 'id', 'username');
$selUserList = \yii\helpers\ArrayHelper::map($selUsers, 'user_id', 'user.username');

$this->title = Yii::t('app/admin', 'Admin user of {group}', ['group' =>$group->name]);
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
                        <label for="galGroupUsers">User of group</label>
                        <?=
                        //                $form->field($group, 'galGroupUsers')->dropDownList($selUserList, ['size' => 10, 'multiple' => 'multiple', 'class' => 'form-control']);
                        Html::dropDownList('galGroupUsers', null, $selUserList, ['size' => 10, 'multiple' => 'multiple', 'id' => 'galgroup-galgroupusers', 'class' => 'form-control']);
                        ?>
                    </div>
                </div>
                <div class="col-xs-6">
                    <div class="form-group">
                        <label for="galusers">Users</label>
                        <?= Html::dropDownList('galusers', null, $userList, ['size' => 10, 'multiple' => 'multiple', 'id' => 'galusers', 'class' => 'form-control']); ?>
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
    $('#galgroup-galgroupusers').listorder({submitAll: true});
    $('#galusers').listorder();
    $('#galgroup-galgroupusers').listorder('duoExclude', $('#galusers'));
    $('#filter1').on('keyup', function () {
        $('#galgroup-galgroupusers').listorder('filter', $(this).val());
        $('#galusers').listorder('filter', $(this).val());
    });
EOT
);


