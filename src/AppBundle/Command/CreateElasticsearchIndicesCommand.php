<?php

namespace AppBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CreateElasticSearchIndicesCommand extends ContainerAwareCommand
{

    protected function configure()
    {
        $this
            ->setName('read_model:create_elasticsearch_indices')
            ->setDescription('Creates the elasticsearch indices')
            ->setHelp(<<<EOT
The <info>%command.name%</info>command creates the elasticsearch indices for the given environment .
EOT
            )
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $indexGenerator = $this->getContainer()->get('read_model.index_generator');
        $indexGenerator->generateElasticsearchIndices();
    }
}
