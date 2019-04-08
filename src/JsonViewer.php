<?php

namespace xlerr\jsoneditor;

use yii\base\Widget;
use yii\helpers\Html;
use yii\widgets\InputWidget;

class JsonViewer extends InputWidget
{
    public $options = [
        'style' => 'height:300px',
    ];

    public $clientOptions = [
        'mode'            => 'code',
        'navigationBar'   => false,
        'mainMenuBar'     => false,
        'statusBar'       => false,
        'enableTransform' => true,
    ];

    public function init()
    {
        if (!isset($this->options['id'])) {
            $this->options['id'] = $this->hasModel() ? Html::getInputId($this->model, $this->attribute) :
                $this->getId();
        }
        Widget::init();
    }

    public function run()
    {
        echo Html::tag('div', null, $this->options);

        $view = $this->getView();

        JsonEditorAsset::register($view);

        $id = $this->options['id'];

        $value = $this->hasModel() ? $this->model->{$this->attribute} : $this->value;
        $value = json_decode($value) === null ? sprintf('"%s"', $value) : $value;

        $clientOptions = json_encode($this->clientOptions);

        $js = <<<JS
new JSONEditor(document.getElementById('$id'), $clientOptions, $value);
JS;
        $view->registerJs($js);
    }
}