<?php
namespace app\widgets\galphostructure;

use yii\base\Widget;

class GalphoStructureWidget extends Widget {

    /**
     * Data folder structure
     * @var array
     */
    public $structure;

    /**
     * path
     * @var string
     */
    public $path;

    /**
     * columns configuration.
     * @var array
     *
     *   field
     *      value : value to display, ie '$data[username]'
     *      title : column title
     */
//    public $columns;

    /**
     * html tag for child line,  ie <ul>
     * @var string
     */
    public $childLineTag = 'div';

    /**
     * html tag for line, ie <li>
     * @var string
     */
    public $lineTag = 'div';

    /**
     * the element attributes for  line,
     * @var string
     */
    public $lineHtmlOption = [];





    public $classOpenItem = 'galpho-open';
    public $classCloseItem = 'galpho-close';
    public $classSelectItem = 'galpho-select';



    public function run() {
        $view = $this->getView();
        GalphoStructureAsset::register($view);

        $options = null;
        $options = empty($options) ? '' : ',' . Json::encode($options);

        $id = $this->getId();
        $js = "jQuery(\"#{$id}\").galphoStructure({$options});";
        $view->registerJs($js);
        $this->header();
        $this->getTree($this->structure, $this->path, []);
        $this->footer();
    }

    public function header() {
    }

    public function footer() {
    }

    public function displayLine(&$element) {
        echo $element['title'];
    }

    protected function _openTab($tab, $class) {
        $html = '<' . $tab;
        if ($class) {
            return $html . ' class="' . $class . '">';
        }
        return $html . '>';
    }

    protected function _closeTab($tab) {
        return '</' . $tab . '>';
    }

    public function getTree(&$structure, $path = null) {
        if (!isset($structure['#']['name'])) {
            return '';
        } else {
            $paths = explode('/', trim($path, '/'));
            $class = $this->classOpenItem;
            if (empty($paths)) {
                $class .=' ' . $this->classSelectItem;
            }
            echo PHP_EOL.'<div class="galphostructure" id="'.$this->getId().'">'.PHP_EOL . $this->_openTab($this->childLineTag, $class . ' galpho-child odd') . '<!-child-->' . PHP_EOL . $this->_openTab($this->lineTag,  $class.' galpho-notlast galpho-line') . '<!-line-->';
            $structure['#']['key'] = '';
            $this->displayLine($structure['#']);
            echo PHP_EOL . $this->_openTab($this->childLineTag, $class . ' galpho-child even') . '<!-child-->';
            $heap = [];
            do {
                $element = current($structure);
                if ($element !== false) {
                    $key = key($structure);
                    if ($key != '#' && isset($element['#']['name'])) {
                        if (current($paths) == $key || $element['#']['level'] < 1) {
                            $class = $this->classOpenItem;
                            if (next($paths) == false) {
                                $class .=' ' . $this->classSelectItem;
                            }
                        } else {
                            $class = $this->classCloseItem;
                        }
                        if(count($element) == 1) {
                            $classLine = $class.' galpho-last';
                        } else {
                            $classLine = $class.' galpho-notlast';
                        }
                        echo PHP_EOL . $this->_openTab($this->lineTag, $classLine. ' galpho-line') . '<!-line-->';
                        $element['#']['key'] = $key;
                        $this->displayLine($element['#']);
                        if (count($element) > 1) {
                            array_push($heap, $structure);
                            $structure = $element;
                            echo PHP_EOL . $this->_openTab($this->childLineTag, $class . ' galpho-child' . ($element['#']['level']%2 ? ' odd' : ' even'))  . '<!-child-->';
                        } else {
                            echo PHP_EOL . $this->_closeTab($this->lineTag) . '<!-/line-->';
                        }
                    }
                } else {

                    if (empty($heap)) {
                        break;
                    }
                    echo PHP_EOL . $this->_closeTab($this->childLineTag) . '<!-/child-->' . PHP_EOL . $this->_closeTab($this->lineTag) . '<!-/line-->' . PHP_EOL;
                    $structure = array_pop($heap);
                }
                next($structure);
            } while ($structure != null);
        }
        echo $this->_closeTab($this->childLineTag) . '<!-/child-->' . PHP_EOL . $this->_closeTab($this->lineTag) . '<!-/line-->' . PHP_EOL . $this->_closeTab($this->childLineTag) . '<!-child-->' . PHP_EOL.'</div>'.PHP_EOL;
    }

}