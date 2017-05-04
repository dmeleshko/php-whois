<?php

namespace Mitrio\PHPWhois;


class Whois
{
    protected $timeout;
    protected $errno;
    protected $errstr;
    protected $last_result;

    /**
     * Whois constructor.
     * @param float $timeout connect timeout in seconds
     */
    public function __construct($timeout = 2)
    {
        $this->timeout = $timeout;
    }

    /**
     * Send whois request to server.
     * Return raw whois data
     * @param string $request request string
     * @param string $server ip or hostname of whois server
     * @return string
     */
    protected function send_request($request, $server, $port=43)
    {
        $sock = fsockopen($server, $port, $this->errno, $this->errstr, $this->timeout);
        if($this->errno) {
            throw new WhoisException("Error while connecting to whois server: {$this->errstr}", $this->errno);
        } elseif($sock === false) {
            throw new WhoisException("Unknown error while connecting to whois server");
        }

        $result = "";
        $chunk_size = 2048;
        fputs($sock, $request . "\r\n");
        while (true) {
            $chunk = fread($sock, $chunk_size);
            $result .= $chunk;
            if(count($chunk) < $chunk_size) {
                break;
            }
        }
        fclose ($sock);
        if(strlen($result) == 0) {
            throw new WhoisException("Empty response returned");
        }
        $this->last_result = $result;
        return $result;
    }

    public function lookup($request)
    {
        $server = 'whois.nic.kz';
        return $this->send_request($request, $server);
    }
}