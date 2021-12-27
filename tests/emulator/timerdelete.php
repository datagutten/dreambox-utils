<?php
if ($_POST['begin'] == 1640592000 && $_POST['end'] == 1640595600)
{
    $xml = new SimpleXMLElement('<e2simplexmlresult></e2simplexmlresult>');
    $xml->addChild('e2state', 'True');
    $xml->addChild('e2statetext', "The timer 'bad' has been deleted successfully");
    $xml->addChild('request', json_encode($_POST));
    echo $xml->asXML();
}