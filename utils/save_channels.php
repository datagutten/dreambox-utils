<?php

use datagutten\dreambox\web\epg;

require __DIR__.'/../vendor/autoload.php';

try {
    $epg = new epg($argv[1]);
    $epg->save_channel_list();
}
catch (Exception $e)
{
    echo $e->getMessage()."\n";
}