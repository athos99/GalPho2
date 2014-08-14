<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;

$userList = \yii\helpers\ArrayHelper::map($users, 'id', 'username');
$selUserList = \yii\helpers\ArrayHelper::map($selUsers, 'id', 'username');

$this->title = Yii::t('app/admin', 'Admin user of group : ' . $group->name);
$this->params['breadcrumbs'][] = $this->title;
?>
    <h1><?= $this->title ?></h1>

    <div class="folder-form">
        <?php
        /*  @var yii\widgets\ActiveForm $form */
        $form = ActiveForm::begin(); ?>


        <div class="controls-row">
            <div class="span3">
                <?=
                $form->field($group, 'galGroupUsers')->dropDownList($selUserList, ['size' => 10, 'multiple' => 'multiple', 'class' => 'form-control']);
                //        echo CHtml::activeDropDownList(GalphoGroupUser::model(), 'galpho_user_id', $selUserList, array('size' => 10, 'multiple' => 'multiple'));
                ?>

            </div>
            <div class="span3">
                <?= Html::dropDownList('user', null, $userList, ['size' => 10, 'multiple' => 'multiple']); ?>
            </div>
        </div>
        <div class="controls-row">
            <div class="span2">
            </div>
            <div class="span3">
                <div class="form-inline">
                    <input id="filter1" class="input-small" type="text" placeholder="search....">
                </div>
            </div>
        </div>

        <div class="controls-row">
            <div class="buttons">
                <?php //echo CHtml::submitButton('Save', array('class' => 'btn btn-primary', 'name' => 'save')); ?>
                <?php //echo CHtml::submitButton('cancel', array('class' => 'btn dialog-close')); ?>
            </div>
        </div>
        <?php ActiveForm::end(); ?>

    </div><!-- form -->
<?php if (0) : ?>
    <?php if (Yii::app()->getRequest()->isAjaxRequest) {

        echo PHP_EOL . '<script type="text/javascript">' . PHP_EOL;
        foreach (Yii::app()->clientScript->scripts as $scripts) {
            foreach ($scripts as $script) {
                echo $script . PHP_EOL;
            }
        }
        echo PHP_EOL . '</script>';
    }?>

    <script type="text/javascript">
        $(document).ready(function () {
            $('#GalphoGroupUser_galpho_user_id').listorder({submitAll: true});
            $('#GalphoUser_id').listorder();
            $('#GalphoGroupUser_galpho_user_id').listorder('duoExclude', $('#GalphoUser_id'));
            $('#filter1').on('keyup', function () {
                $('#GalphoGroupUser_galpho_user_id').listorder('filter', $(this).val());
                $('#GalphoUser_id').listorder('filter', $(this).val());
            });
        });
    </script>
<?php endif; ?>