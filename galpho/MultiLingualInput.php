<?php
namespace app\galpho;

use yii\base\InvalidConfigException;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\InputWidget;

/**
 * MultiLingualInput generates text input for each langue.
 *
 * ```php
 * echo MaskedInput::widget([
 *     'languages' => ['en'=>'english', 'fr','french'],
 *     'type' => 'text/textarea'
 *     ,
 * ]);
 * ```
 */
class MultiLingualInput extends InputWidget
{


    public $languages;
    public $type;
    public $labelOptions = ['class' => 'control-label'];
    public $inputOptions = ['class' => 'form-control'];
    public $divInputOptions = ['class' => ''];


    /**
     * Initializes the widget.
     *
     */
    public function init()
    {
        parent::init();
        if (empty($this->languages)) {
            throw new InvalidConfigException("The 'language' property must be set.");
        }

    }

    /**
     * @inheritdoc
     */
    public function run()
    {
        if ($this->hasModel()) {
            $this->name = Html::getInputName($this->model, $this->attribute);
            $this->value = Html::getAttributeValue($this->model, $this->attribute);
        }
        if (!array_key_exists('id', $this->inputOptions)) {
            $this->inputOptions['id'] = $id = Html::getInputId($this->model, $this->attribute);
        }
        foreach ($this->languages as $key => $language) {
            echo Html::beginTag('div',['class'=>'form-group']);
            echo Html::label($language, $this->inputOptions['id'],$this->labelOptions);
            echo Html::beginTag('div',$this->divInputOptions);
            if ($this->type === 'textarea') {
                echo Html::textarea($this->name . '[' . $key . ']', ArrayHelper::getValue($this->value, $key), $this->inputOptions);

            } else {
                echo Html::textInput($this->name . '[' . $key . ']', ArrayHelper::getValue($this->value, $key), $this->inputOptions);
            }
            echo Html::endTag('div');
            echo Html::endTag('div');
            $this->inputOptions['id'] = $id . '_' . $key;
        }
    }
}
