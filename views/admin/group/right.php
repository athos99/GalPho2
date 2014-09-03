<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/**
 * @var $this yii\web\View
 * @var $group app\models\GalGroup
 * @var pathStructure []
 *
 */
class TreeGalphoStructureWidget extends app\widgets\galphostructure\GalphoStructureWidget
{

    public $rights;

    public function init()
    {
        parent::init();
    }


    function header()
    {
        foreach ($this->rights as $i => $name) {
            ?>
            <span><?= $name ?></span>
        <?php
        }
    }

    function displayRight($right, $id)
    {
        ?>
        <span>admin</span>
        <span>editor</span>
        <?php
        foreach ($this->rights as $i => $name) {
            $mask = 1 << $i;
            $check = ($right & $mask) ? 'checked="checked"' : '';
            $name = 'r[' . $id . '][]';
            $class = 'r' . $i;
            ?>
            <span class="<?= $class ?>"><input type="checkbox" name="<?= $name ?>" value="<?= $mask?>" <?= $check ?>> </span>
        <?php
        }
    }

    function displayLine(&$element)
    {
        ?>
        <div class="">
            <div class="galpho-name galpho-left"><span
                    style="margin-left: <?php echo $element['level'] * 8; ?>px"></span>
                <?php echo \yii\helpers\Html::encode($element['title']); ?></div>
            <div class="galpho-right">
                <?= $this->displayRight($element['right'], $element['id']) ?>
            </div>
        </div>

    <?php
    }

}


$this->title = Yii::t('app/admin', 'Right for group {group}', ['group' => $group->name]);
$this->params['breadcrumbs'][] = $this->title;
$rights = Yii::$app->params['right'];
?>

    <h1><?= Html::encode($this->title); ?></h1>
<?php
/*  @var yii\widgets\ActiveForm $form */
$form = ActiveForm::begin(); ?>

    <div class="row">
        <?=
        TreeGalphoStructureWidget::widget([
            'structure' => $pathStructure,
            'childLineTag' => 'ul',
            'lineTag' => 'li',
            'path' => '',
            'rights' => $rights
        ]);

        ?>
    </div>
    <div class="row">
        <div class="form-group">
            <?= Html::resetButton(\Yii::t('app/admin', 'Reset'), ['class' => 'btn btn-default']); ?>
            <?= Html::submitButton(\Yii::t('app/admin', 'Cancel'), ['class' => 'btn btn-default  no-validation', 'name' => 'cancel']) ?>
            <?= Html::submitButton(\Yii::t('app/admin', 'Save'), ['class' => 'btn btn-primary', 'name' => 'save']) ?>
        </div>
    </div>
<?php ActiveForm::end(); ?>