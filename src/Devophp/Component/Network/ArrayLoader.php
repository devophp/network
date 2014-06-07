<?php

namespace Devophp\Component\Network;

use Devophp\Component\Network\Network;
use Devophp\Component\Network\Node;
use Devophp\Component\Network\NodeClass;
use Devophp\Component\Network\IpRule;
use Devophp\Component\Network\IpRuleAddress;
use Devophp\Component\Network\NodeNetworkInterface;

class ArrayLoader
{
    
    public function load(array $data)
    {
        $network = new Network();

        foreach ($data['classes'] as $nodeclassdata) {
            $nodeclass = new NodeClass();
            $nodeclass->setId($nodeclassdata['id']);
            $network->registerNodeClass($nodeclass);
            foreach ($nodeclassdata['iprules'] as $ipruledata) {
                $iprule = new IpRule();
                $iprule->setName($ipruledata['name']);
                $iprule->setDescription($ipruledata['description']);
                $iprule->setChain($ipruledata['chain']);
                $iprule->setProtocol($ipruledata['protocol']);

                $ipruleaddress = new IpRuleAddress($ipruledata['destination']);
                $iprule->setDestination($ipruleaddress);

                $ipruleaddress = new IpRuleAddress($ipruledata['source']);
                $iprule->setSource($ipruleaddress);

                $iprule->setAction($ipruledata['action']);
                $nodeclass->registerIpRule($iprule);
            }
        }

        foreach ($data['nodes'] as $nodedata) {
            $node = new Node();
            $node->setId($nodedata['id']);
            $node->setDescription($nodedata['description']);
            $network->registerNode($node);
            
            foreach ($nodedata['interfaces'] as $interfacedata) {
                $networkinterface = new NodeNetworkInterface();
                $networkinterface->setId($interfacedata['id']);
                $networkinterface->setIp($interfacedata['ip']);
                $networkinterface->setClassNames($interfacedata['classes']);

                $node->registerNetworkInterface($networkinterface);
            }
            $node->setClassNames($nodedata['classes']);

        }
        return $network;
    }
}
