<?php

namespace bmwx591\googleyolo;


use yii\web\AssetBundle;
use yii\web\View;

class GoogleYoloAsset extends AssetBundle
{
    public $sourcePath = '@vendor/bmwx591/yii2-googleyolo';

    public $publishOptions = [];

    public $jsOptions = [
        'position' => View::POS_HEAD
    ];

    public $js = [
        'https://smartlock.google.com/client',
        'js/yolo.js'
    ];
}