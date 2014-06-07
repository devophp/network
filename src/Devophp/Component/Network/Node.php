<?php

namespace Devophp\Component\Network;

use Devophp\Component\Network\Network;
use Devophp\Component\Network\NodeNetworkInterface;

class Node extends Resource
{
    private $networkinterfaces = array();
    
    public function registerNetworkInterface(NodeNetworkInterface $networkinterface)
    {
        $this->networkinterfaces[$networkinterface->getId()] = $networkinterface;
    }
    
    public function getNetworkInterfaces()
    {
        return $this->networkinterfaces;
    }
}
