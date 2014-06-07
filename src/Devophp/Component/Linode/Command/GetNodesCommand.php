<?php 

namespace Devophp\Component\Linode\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Yaml\Parser as YamlParser;
use Devophp\Component\Network\ArrayLoader;
use Devophp\Component\Network\pRule;
use Linode\Api as LinodeApi;
use Symfony\Component\Yaml\Dumper as YamlDumper;
use RuntimeException;

class GetNodesCommand extends Command
{
    protected function configure()
    {
        $this
            ->setName('linode:getnodes')
            ->setDescription(
                'Load info from Linode into a .yml file'
            )
            ->addArgument(
                'apikey',
                InputArgument::REQUIRED,
                'filename'
            );
    }
    
    public function execute(InputInterface $input, OutputInterface $output)
    {
        $apikey = $input->getArgument('apikey');
        $output->write("Connecting with apikey '" . $apikey ."'\n");
        
        $linode = new LinodeApi($apikey);
        
        $data = array();
        $linodesresponse = $linode->linode_list();
        foreach ($linodesresponse['DATA'] as $linodedata) {
            $linodeid = $linodedata['LINODEID'];
            $node = array();
            $node['id'] = $linodedata['LABEL'];
            $description = 'hd: ' . $linodedata['TOTALHD'] . ' ram: ' . $linodedata['TOTALRAM'];
            $description .= ' ' . $linodedata['DISTRIBUTIONVENDOR'];
            $description .= $linodeid . '/' . $linodedata['LPM_DISPLAYGROUP'];
            $description .= ' DC' . $linodedata['DATACENTERID'];
            $description .= ' status=' . $linodedata['STATUS'];
            $node['description'] = $description;
            $node['interfaces'] = array();

            $ipsresponse = $linode->linode_ip_list($linodeid);
            foreach($ipsresponse['DATA'] as $ipdata) {
                $interface = array();
                $interface['ip'] = $ipdata['IPADDRESS'];
                $interface['description'] = $ipdata['RDNS_NAME'] . ' id:' . $ipdata['IPADDRESSID'] . "]";
                $interface['public'] = $ipdata['ISPUBLIC'];
                $node['interfaces'][] = $interface;
            }
            $data['nodes'][] = $node;
            //echo  "\n";
            
            
        }
        
        $yamldumper = new YamlDumper();
        $yaml = $yamldumper->dump($data, 8);
        
        echo($yaml);
        exit();
    }
}
