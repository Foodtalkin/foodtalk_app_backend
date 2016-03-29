<?php
defined('YII_DEBUG') or define('YII_DEBUG',true);

require_once ('../../utilFunctions.php');
require_once ('../../vgThumb.php');

// including Yii
require_once('../../../yiiroot/framework/yii.php');

// we'll use a separate config file
$configFile='../config/cron.php';

// creating and running console application

Yii::createConsoleApplication($configFile)->run();
