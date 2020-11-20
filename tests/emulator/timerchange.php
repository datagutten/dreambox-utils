<?php
if($_POST['begin']==1606056900)
    echo file_get_contents(__DIR__.'/data/timerchange_error.xml');
else
{
    $xml = new SimpleXMLElement('<e2simplexmlresult></e2simplexmlresult>');
    $xml->addChild('e2state', 'True');
    $xml->addChild('e2statetext', sprintf("Timer '%s' added", $_POST['name']));
    $xml->addChild('request', json_encode($_POST));
    echo $xml->asXML();
}