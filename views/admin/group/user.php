<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\assets\ListorderAsset;

/**
 * @var yii\web\View $this
 */


ListorderAsset::register($this);
$userList = \yii\helpers\ArrayHelper::map($users, 'id', 'username');
$selUserList = \yii\helpers\ArrayHelper::map($selUsers, 'id', 'username');

$this->title = Yii::t('app/admin', 'Admin user of group : ' . $group->name);
$this->params['breadcrumbs'][] = $this->title;
?>
    <h1><?= $this->title ?></h1>

    <div class="form-group">
        <?php
        /*  @var yii\widgets\ActiveForm $form */
        $form = ActiveForm::begin(); ?>

        <div class="row">
            <div class="col-xs-12">
                <div class="row">
                    <div class="col-xs-6">
                        <label for="galGroupUsers">User of group</label>
                        <?=
                        //                $form->field($group, 'galGroupUsers')->dropDownList($selUserList, ['size' => 10, 'multiple' => 'multiple', 'class' => 'form-control']);
                        Html::dropDownList('galGroupUsers', null, $selUserList, ['size' => 10, 'multiple' => 'multiple', 'id' => 'galgroup-galgroupusers', 'class' => 'form-control']);
                        ?>
                    </div>
                    <div class="col-xs-6">
                        <label for="galuser">Users</label>
                        <?= Html::dropDownList('galusers', null, $userList, ['size' => 10, 'multiple' => 'multiple', 'id' => 'galusers', 'class' => 'form-control']); ?>
                    </div>
                </div>
            </div>
        </div>
        <div class="form-group">
                    <label for="filter1" class="col-xs-2 control-label">Filter</label>
                    <input id="filter1" class="col-xs-8" type="text" placeholder="search....">
        </div>

        <div class="controls-row">
            <div class="buttons">
                <?php //echo CHtml::submitButton('Save', array('class' => 'btn btn-primary', 'name' => 'save')); ?>
                <?php //echo CHtml::submitButton('cancel', array('class' => 'btn dialog-close')); ?>
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


