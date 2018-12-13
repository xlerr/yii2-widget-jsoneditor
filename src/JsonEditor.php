<?php

namespace xlerr\jsoneditor;

use yii\helpers\Html;
use yii\widgets\InputWidget;

class JsonEditor extends InputWidget
{
    public $height = '500px';

    public function run()
    {
        if ($this->hasModel()) {
            $input = Html::activeHiddenInput($this->model, $this->attribute, $this->options);
        } else {
            $input = Html::hiddenInput($this->name, $this->value, $this->options);
        }

        $id     = $this->options['id'];
        $textId = $id . '-text';
        $treeId = $id . '-tree';

        echo <<<HTML
$input
<div class="row">
    <div id="$textId" class="col-sm-6" style="height: {$this->height};"></div>
    <div id="$treeId" class="col-sm-6" style="height: {$this->height};"></div>
</div>
HTML;

        $this->registerClientScript();
    }

    public function registerClientScript()
    {
        $view = $this->getView();

        JsonEditorAsset::register($view);

        $id         = $this->options['id'];
        $textId     = $id . '-text';
        $treeId     = $id . '-tree';
        $idHash     = md5($id);
        $textIdHash = md5($textId);
        $treeIdHash = md5($treeId);

        $value = $this->hasModel() ? $this->model->{$this->attribute} : $this->value;
        $value = json_decode($value) === null ? sprintf('"%s"', $value) : $value;

        $js = <<<JS
var jsoneditor$idHash = document.getElementById('$id');
var json$idHash = $value;
var jsoneditor$textIdHash = new JSONEditor(document.getElementById('$textId'), {
    mode: 'code',
    onChangeText: function (jsonString) {
        try {
            jsoneditor$treeIdHash.setText(jsonString);
            jsoneditor$idHash.value = jsoneditor$treeIdHash.getText().replace(/^\"|\"$/g, '');
        } catch (e) {}
    }
}, json$idHash);
var jsoneditor$treeIdHash = new JSONEditor(document.getElementById('$treeId'), {
    mode: 'tree',
    search: false,
    onChangeJSON: function (json) {
        jsoneditor$textIdHash.set(json);
    },
    onChangeText: function (jsonString) {
        jsoneditor$idHash.value = jsonString.replace(/^\"|\"$/g, '');
    }
}, json$idHash);
JS;
        $view->registerJs($js);
    }
}