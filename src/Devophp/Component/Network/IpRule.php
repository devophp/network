<?php

namespace Devophp\Component\Network;

use Devophp\Component\Network\Network;
use Devophp\Component\Network\IpRuleAddress;
use RuntimeException;

class IpRule
{
    private $name;
    public function setName($name)
    {
        $this->name = $name;
    }
    public function getName()
    {
        return $this->name;
    }

    private $description;
    public function setDescription($description)
    {
        $this->description = $description;
    }
    public function getDescription()
    {
        return $this->description;
    }

    private $chain;
    public function setChain($chain)
    {
        $this->chain = $chain;
    }
    public function getChain()
    {
        return $this->chain;
    }

    private $source;
    public function setSource(IpRuleAddress $source)
    {
        $this->source = $source;
    }
    
    public function getSource()
    {
        return $this->source;
    }

    private $destination;
    public function setDestination(IpRuleAddress $destination)
    {
        $this->destination = $destination;
    }
    public function getDestination()
    {
        return $this->destination;
    }
    
    
    
    private $protocol;
    public function setProtocol($protocol)
    {
        $this->protocol = $protocol;
    }
    public function getProtocol()
    {
        return $this->protocol;
    }

    private $action;
    public function setAction($action)
    {
        $this->action = $action;
    }
    public function getAction()
    {
        return $this->action;
    }
    
    
    public function getIpTablesRules($network)
    {
        $addresses = array();
        $iprule = $this;
        $o = '## ' . $iprule->getName() . "\n";
        
        $source = $iprule->getSource();
        $destination = $iprule->getDestination();
        
        $sport = $source->getPort();
        $dport = $destination->getPort();
        
        $sourceaddresses = $source->getAddressArray($network);
        $destinationaddresses = $destination->getAddressArray($network);
        
        foreach ($destinationaddresses as $destinationaddress) {
            foreach ($sourceaddresses as $sourceaddress) {
                $o .= '-A ';
                $o .= $iprule->getChain();
                if ($sourceaddress!='') {
                    $o .= ' -s ' . $sourceaddress;
                }
                if ($destinationaddress!='') {
                    $o .= ' -d ' . $destinationaddress;
                }
                
                $protocol = $iprule->getProtocol();
                if ($protocol) {
                    $o .= ' -p ' . $protocol;
                }
                if ($dport) {
                    $o .= ' --dport ' . str_replace('-', ':', $dport);
                }
                
                if ($sport) {
                    $o .= ' --sport ' . str_replace('-', ':', $sport);
                }
                $o .= ' -j ' . strtoupper($iprule->getAction());
                $o .= ' -m comment --comment ' . escapeshellarg($iprule->getName());
                $o .= "\n";
            }
        }
        return $o . "\n";
    }
}
