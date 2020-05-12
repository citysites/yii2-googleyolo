<?php

namespace citysites\googleyolo;


use yii\web\AssetBundle;
use yii\web\View;

class GoogleYoloAsset extends AssetBundle
{
    public $sourcePath = '@vendor/citysites/yii2-googleyolo';

    public $jsOptions = [
        'position' => View::POS_HEAD
    ];

    public $js = [
        'https://smartlock.google.com/client',
        'js/yolo.js'
    ];
}