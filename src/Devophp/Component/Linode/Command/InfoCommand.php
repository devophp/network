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
use RuntimeException;

class InfoCommand extends Command
{
    protected function configure()
    {
        $this
            ->setName('linode:info')
            ->setDescription(
                'Load info from Linode'
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

        $linodesresponse = $linode->linode_list();
        foreach ($linodesresponse['DATA'] as $linodedata) {
            $linodeid = $linodedata['LINODEID'];
            echo $linodedata['LPM_DISPLAYGROUP'] . '/' . $linodedata['LABEL'] . "(ID:" .$linodeid. ")\n";
            echo ' - datacenterid: ' .$linodedata['DATACENTERID'] . "\n";
            echo ' - distribution: ' .$linodedata['DISTRIBUTIONVENDOR'] . "\n";
            echo ' - status: ' .$linodedata['STATUS'] . "\n";
            echo ' - hd: ' . $linodedata['TOTALHD'] . ' ram: ' . $linodedata['TOTALRAM'] . "\n";
            $ipsresponse = $linode->linode_ip_list($linodeid);
            foreach($ipsresponse['DATA'] as $ipdata) {
                echo ' - ' . $ipdata['IPADDRESS'] . " (" . $ipdata['RDNS_NAME'] . ') ';
                if ($ipdata['ISPUBLIC']) {
                    echo "PUBLIC";
                } else {
                    echo "PRIVATE";
                }
                echo " [id:" . $ipdata['IPADDRESSID'] . "]\n";
            }
            echo  "\n";
        }
        
        print_r($linodesresponse);
    }
}
