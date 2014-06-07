<?php

namespace Devophp\Component\Network;

use Devophp\Component\Network\Network;

class NodeNetworkInterface extends Resource
{

    private $ip;
    public function setIp($ip)
    {
        $this->ip = $ip;
    }
    
    public function getIp()
    {
        return $this->ip;
    }
}
