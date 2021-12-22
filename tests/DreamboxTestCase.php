<?php

namespace datagutten\dreambox\web_tests;

use PHPUnit\Framework\TestCase;


abstract class DreamboxTestCase extends TestCase
{
    /**
     * @var string IP to dreambox
     */
    public string $dreambox_ip;

    function setUp(): void
    {
        $ip_env = getenv('DREAMBOX_IP');
        if (!empty($ip_env))
            $this->dreambox_ip = $ip_env;
        else
            $this->dreambox_ip = '127.0.0.1';
    }
}