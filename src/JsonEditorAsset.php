<?php

namespace xlerr\jsoneditor;

use yii\web\AssetBundle;

class JsonEditorAsset extends AssetBundle
{
    public $sourcePath = '@bower/jsoneditor/dist';

    public $css = [
        'jsoneditor.min.css',
    ];

    public $js = [
        'jsoneditor.min.js',
    ];
}
