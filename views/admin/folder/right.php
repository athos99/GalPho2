<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/**
 * @var yii\base\View $this
 * @var yii\db\ActiveRecord $records []
 * @var app\models\GalGroup $galGroup
 */


$this->title = Yii::t('app/admin', 'Folder rights');
$this->params['breadcrumbs'][] = $this->title;
$rights = Yii::$app->params['right'];

?>
<h1><?= $this->title ?></h1><?php
/*  @var yii\widgets\ActiveForm $form */
$form = ActiveForm::begin();
?>
<div class="gal-group-index">

    <table>
        <thead>
        <tr>
            <th><?= $galGroup->getAttributeLabel('name') ?></th>
            <th><?= $galGroup->getAttributeLabel('description') ?></th>
            <th><?= Yii::t('app/admin', 'rights') ?><br><?php
                foreach ($rights as $i => $name) :
                    ?><span><?= $name ?></span><?php
                endforeach;?>
            </th>
        </tr>
        </thead>
        <tbody>
        <?php
        foreach ($records as $record) :
            ?>
            <tr>
                <td><?= Html::encode($record->name) ?></td>
                <td><?= Html::encode($record->description) ?></td>
                <td><?php
                    $right = empty($record->galRights[0]->value) ? 0 : $record->galRights[0]->value;
                    foreach ($rights as $i => $name) :
                        $mask = 1 << $i;
                        $check = ($right & $mask) ? 'checked="checked"' : '';
                        $name = 'r[' . $record->id . '][]';
                        $class = 'r' . $i;
                        ?>
                        <span class="<?= $class ?>"><input type="checkbox" name="<?= $name ?>"
                                                           value="<?= $mask ?>" <?= $check ?>> </span>
                    <?php
                    endforeach;
                    ?></td>
            </tr>
        <?php
        endforeach;
        ?>
        </tbody>
    </table>

    <div><?= Html::checkbox('children',false,['label'=>Yii::t('app/admin','Apply to children folder')])?></div>

    <div class="row">
        <div class="form-group">
            <?= Html::resetButton(Yii::t('app/admin', 'Reset'), ['class' => 'btn btn-default']); ?>
            <?= Html::submitButton(Yii::t('app/admin', 'Cancel'), ['class' => 'btn btn-default  no-validation', 'name' => 'cancel']) ?>
            <?= Html::submitButton(Yii::t('app/admin', 'Save'), ['class' => 'btn btn-primary', 'name' => 'save']) ?>
        </div>
    </div>
</div>
<?php ActiveForm::end(); ?>