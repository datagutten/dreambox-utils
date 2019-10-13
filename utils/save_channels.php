#!/usr/bin/php
<?php

use datagutten\dreambox\web\epg;

if(file_exists(__DIR__.'/../../../autoload.php'))
    require __DIR__.'/../../../autoload.php';
else
    require __DIR__.'/../vendor/autoload.php';

try {
    $epg = new epg($argv[1]);
    $epg->save_channel_list();
}
catch (Exception $e)
{
    echo $e->getMessage()."\n";
}