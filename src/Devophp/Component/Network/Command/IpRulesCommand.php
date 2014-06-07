<?php 

namespace Devophp\Component\Network\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Yaml\Parser as YamlParser;
use Devophp\Component\Network\ArrayLoader;
use Devophp\Component\Network\pRule;
use RuntimeException;

class IpRulesCommand extends Command
{
    protected function configure()
    {
        $this
            ->setName('network:iprules')
            ->setDescription(
                'Parse config file'
            )
            ->addArgument(
                'filename',
                InputArgument::REQUIRED,
                'filename'
            );
    }
    
    public function execute(InputInterface $input, OutputInterface $output)
    {
        $filename = $input->getArgument('filename');
        $output->write("Parsing '" . $filename ."'\n");
        
        $yamlparser = new YamlParser();
        $configdata = $yamlparser->parse(file_get_contents($filename));
        //print_r($configdata);
        
        $loader = new ArrayLoader();
        $network = $loader->load($configdata);
        //print_r($network);
        
        $nodeid = 'lamp-server-1';
        $rules .= '# iptables rules for node #' . $nodeid . "\n";
        $node = $network->getNodeById($nodeid);
        $nodeclassnames = $node->getClassNames();
        foreach ($nodeclassnames as $nodeclassname) {
            $nodeclass = $network->getNodeClassById($nodeclassname);
            foreach ($nodeclass->getIpRules() as $iprule) {
                $rules .= $iprule->getIpTablesRules($network);
            }
        }
        exit($rules);
    }
}
