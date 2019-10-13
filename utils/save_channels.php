#!/usr/bin/php
<?php

use datagutten\dreambox\web\epg;

if(file_exists(__DIR__.'/../vendor/autoload.php'))
    require __DIR__.'/../vendor/autoload.php';
else
    require '../autoload.php';

try {
    $epg = new epg($argv[1]);
    $epg->save_channel_list();
}
catch (Exception $e)
{
    echo $e->getMessage()."\n";
}