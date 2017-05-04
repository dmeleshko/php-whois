<?php

use Mitrio\PHPWhois\Whois;
use PHPUnit\Framework\TestCase;

final class WhoisTest extends TestCase
{
    public $whois;

    public function __construct($name = null, array $data = [], $dataName = '')
    {
        parent::__construct($name, $data, $dataName);
        $this->whois = new Whois();
    }


    public function testLookupReturnNonEmptyResponse()
    {
        $this->assertNotEmpty($this->whois->lookup('alg.kz'));
    }

    public function testLookupReturnFullResponse()
    {
        $this->assertContains("Current Registar", $this->whois->lookup('alg.kz'));
    }
}
