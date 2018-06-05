<?php

namespace bmwx591\googleyolo;


use yii\web\AssetBundle;

class GoogleYoloAsset extends AssetBundle
{
    public $sourcePath = '@vendor/bmwx591/yii2-googleyolo';

    public $js = [
        'https://smartlock.google.com/client',
        'js/yolo.js'
    ];
}