<?php
/**
 * Created by PhpStorm.
 * User: zein
 * Date: 7/3/14
 * Time: 3:14 PM
 */

namespace backend\assets;

use common\assets\AdminLte;
use common\assets\Html5shiv;
use yii\web\AssetBundle;
use yii\web\YiiAsset;
use rmrevin\yii\fontawesome\NpmFreeAssetBundle;

class BackendAsset extends AssetBundle
{
    /**
     * @var string
     */
    public $sourcePath = '@backend/web/bundle';

    /**
     * @var array
     */
    public $css = [
//        '/css/style.css'
    ];
    /**
     * @var array
     */
    public $js = [
        '/js/app.js'
    ];

    public $publishOptions = [
        'only' => [
            '*.css',
            '*.js',
            '../img/*'
        ],
        "forceCopy" => YII_ENV_DEV,
//        "forceCopy" => true,
    ];

    /**
     * @var array
     */
    public $depends = [
        YiiAsset::class,
        AdminLte::class,
        Html5shiv::class,
    ];
}
