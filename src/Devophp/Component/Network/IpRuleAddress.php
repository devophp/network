<?php

namespace Devophp\Component\Network;

use Devophp\Component\Network\Node;
use Devophp\Component\Network\NodeClass;
use Devophp\Component\Network\Network;
use RuntimeException;

class IpRuleAddress
{
    public function __construct($address)
    {
        $part = explode(':', $address);
        $this->setAddress($part[0]);
        $this->setPort($part[1]);
    }

    private $address;
    public function setAddress($address)
    {
        $this->address = $address;
    }
    public function getAddress()
    {
        return $this->address;
    }

    private $port; // port number or range
    public function setPort($port)
    {
        $this->port = $port;
    }
    
    public function getPort()
    {
        return $this->port;
    }
    
    public function getAddressType()
    {
        $address = $this->address;
        if ($address=='') {
            return 'empty';
        }
        $num="([0-9]|1?\d\d|2[0-4]\d|25[0-5])";
        $range="([0-9]|1\d|2\d|3[0-2])";
        
        $pattern = "/^$num\.$num\.$num\.$num$/";
        if (preg_match($pattern, $address) === 1) {
            return 'ipv4';
        }
        
        $pattern = "/^$num\.$num\.$num\.$num(\/$range)?$/";
        if (preg_match($pattern, $address) === 1) {
            return 'ipv4-cidr';
        }
    
        return 'unknown';
    }
    
    public function getAddressArray(Network $network)
    {
        $addresses = array();
        
        switch($this->getAddressType()) {
            case "ipv4":
            case "ipv4-cidr":
                $addresses[] = $this->getAddress();
                break;
            case 'unknown':
                $addresses = $this->getAddressArrayAdvanced($network);
                //throw new RuntimeException("Unknown address type in iprule: " . $iprule->getAddress());
                break;
            case 'empty':
                $addresses[] = '';
                break;
            default:
                throw new RuntimeException("Unsupported network address in iprule: " . $this->getAddress());
                break;
        }
        return $addresses;
    }
    
    private function getAddressArrayAdvanced(Network $network)
    {
        $selector = trim($this->getAddress());
        $selector = str_replace('   ', ' ', $selector);
        $selector = str_replace('  ', ' ', $selector);
        //echo 'Selector: ' . $selector . "\n";
        $networkinterfaces = array();

        $selectorpaths = explode(' ', $selector);
        foreach ($selectorpaths as $key => $value) {
            //echo $key . '=' . $value . "!\n";
            $selectorpart= explode('.', $value);
            $selectortype = $selectorpart[0];
            $selectorclass = $selectorpart[1];
            //echo "S " . $selectortype . ':' . $selectorclass . "\n";
            if ($key == 0) {
                switch($selectortype) {
                    case 'node':
                        $nodes = $network->getNodesByClassName($selectorclass);
                        foreach ($nodes as $n) {
                            foreach ($n->getNetworkInterfaces() as $networkinterface) {
                                $networkinterfaces[] = $networkinterface;
                            }
                        }
                        break;
                    default:
                        throw new RuntimeException('Unsupported root selector type: ' . $selectortype);
                        break;
                }
            } else {
                switch($selectortype) {
                    case 'networkinterface':
                        $filtered = array();
                        foreach ($networkinterfaces as $networkinterface) {
                            if ($networkinterface->hasClassName($selectorclass)) {
                                $filtered[] = $networkinterface;
                            }
                        }
                        $networkinterfaces = $filtered;
                        break;
                    default:
                        throw new RuntimeException('Unsupported sub selector type: ' . $selectortype);
                        break;
                }
            }
        }
        
        foreach ($networkinterfaces as $networkinterface) {
            $addresses[] = $networkinterface->getIp();
        }
        return $addresses;

    }
}
