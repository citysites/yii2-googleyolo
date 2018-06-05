<?php

namespace bmwx591\googleyolo;


use yii\web\AssetBundle;

class GoogleYoloAsset extends AssetBundle
{
    public $sourcePath = '/';

    public $js = [
        'https://smartlock.google.com/client',
        'js/yolo.js'
    ];
}