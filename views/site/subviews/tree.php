<?php
/**
 * @var app\galpho\Galpho $galpho
 * @var yii\Web\View $this
 */

class TreeGalphoStructureWidget extends app\widgets\galphostructure\GalphoStructureWidget
{

    public $charset;
    public $baseUrl ;

    public function init()
    {
        parent::init();
    }

    function displayLine(&$element)
    {
        ?>
        <div class="galpho-name"><span class="galpho-puce" style="margin-left: <?php echo $element['level'] * 8; ?>px"></span>
                <a href="<?php echo $this->baseUrl.$element['path']?>">
                    <?php echo \yii\helpers\Html::encode($element['title']); ?></a></div>
    <?php
    }

}

echo TreeGalphoStructureWidget::widget( [
    'structure'=>$galpho->getFullStructure(),
    'childLineTag' => 'ul',
    'lineTag'=>'li',
    'path'=>$galpho->getPath(),
    'baseUrl' => $galpho->url

]);
