<?php

namespace Devophp\Component\Network;

use Devophp\Component\Network\Node;
use Devophp\Component\Network\NodeClass;

class Network
{
    private $nodes = array();
    
    public function registerNode(Node $node)
    {
        $this->nodes[$node->getId()] = $node;
        //$node->setNetwork($this);
    }
    
    private $nodeclasses = array();
    public function registerNodeClass(NodeClass $nodeclass)
    {
        $this->nodeclasses[$nodeclass->getId()] = $nodeclass;
        //$node->setNetwork($this);
    }
    
    public function getNodeById($id)
    {
        return $this->nodes[$id];
    }
    
    public function getNodesByClassName($nodeclassname)
    {
        if ($nodeclassname=='') {
            return $this->nodes;
        }
        
        $res = array();
        foreach ($this->nodes as $n) {
            if ($n->hasClassName($nodeclassname)) {
                $res[] = $n;
            }
        }
        return $res;
    }

    public function getNodeClassById($id)
    {
        return $this->nodeclasses[$id];
    }
}
