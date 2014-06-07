<?php

namespace Devophp\Component\Network;

use Devophp\Component\Network\Network;
use Devophp\Component\Network\IpRule;

class NodeClass extends Resource
{
    private $iprules = array();
    public function registerIpRule(IpRule $iprule)
    {
        $this->iprules[$iprule->getName()] = $iprule;
        //$node->setNetwork($this);
    }
    
    public function getIpRules()
    {
        return $this->iprules;
    }
}
