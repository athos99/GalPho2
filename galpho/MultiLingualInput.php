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
    /**
     * @var array the HTML attributes for the input tag.
     * @see \yii\helpers\Html::renderTagAttributes() for details on how attributes are being rendered.
     */
    public $options = ['class' => 'form-control'];


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
            $id = Html::getInputId($this->model, $this->attribute);
        }
        if (!array_key_exists('id', $this->options)) {
            $this->options['id'] = $id;
        }
        echo Html::beginTag('div');
        foreach ($this->languages as $key => $language) {
            echo Html::label($language);
            if ($this->type === 'textarea') {
                echo Html::textarea($this->name . '[' . $key . ']', ArrayHelper::getValue($this->value, $key), $this->options);

            } else {
                echo Html::textInput($this->name . '[' . $key . ']', ArrayHelper::getValue($this->value, $key), $this->options);
            }
        }
        echo Html::endTag('div');
        unset($this->options['id']);
    }
}
