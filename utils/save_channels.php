#!/usr/bin/php
<?php

use datagutten\dreambox\web\epg;

require $_composer_autoload_path ?? __DIR__ . '/../vendor/autoload.php';

try {
    $epg = new epg($argv[1]);
    $file = filnavn($argv[1]);
    $epg->save_channel_list($file);
    printf('Channels saved as %s', $file);
}
catch (Exception $e)
{
    echo $e->getMessage()."\n";
}