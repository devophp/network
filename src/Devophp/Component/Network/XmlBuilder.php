<?php

namespace Devophp\Component\Network;

use Devophp\Component\Network\Network;
use SimpleXMLElement;
use DOMDocument;
use DOMElement;

class XmlBuilder
{
    public function build(Network $network)
    {
        
        //$dom->appendChild($e);
        $doc = new DOMDocument();
        $doc->preserveWhiteSpace = false;
        $doc->formatOutput = true;
        
        //$element = $this->getFullXmlElement($doc);
        $networknode = new DOMElement('network');
        $doc->appendChild($networknode);
        $networknode->setattribute("lalaal", 'lil');
        $networknode->setattribute("class", 'ams prod');

        $element2 = new DOMElement('xyz');
        $networknode->appendChild($element2);
        $element2->setattribute("id", 'hah');
        
        
        
        return $doc;
    }
}
