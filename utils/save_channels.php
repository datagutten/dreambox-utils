#!/usr/bin/php
<?php

use datagutten\dreambox\web\epg;

require __DIR__ . '/loader.php';

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