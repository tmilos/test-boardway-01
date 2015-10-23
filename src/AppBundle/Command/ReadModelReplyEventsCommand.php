<?php

namespace AppBundle\Command;

use AppBundle\Infra\EventPlayer\DBALEventPlayer;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class ReadModelReplyEventsCommand extends ContainerAwareCommand
{
    /** @var DBALEventPlayer */
    private $player;

    /**
     * @param DBALEventPlayer $player
     */
    public function __construct(DBALEventPlayer $player)
    {
        parent::__construct();

        $this->player = $player;
    }

    protected function configure()
    {
        $this
            ->setName('read_model:reply_events')
            ->addOption('start', null, InputOption::VALUE_REQUIRED, 'Play events from Start date')
            ->addOption('end', null, InputOption::VALUE_REQUIRED, 'Play events to End date')
            ->addOption('type', null, InputOption::VALUE_REQUIRED | InputOption::VALUE_IS_ARRAY, 'Play only these types')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $bus = $this->getContainer()->get('broadway.event_handling.event_bus');

        $this->player->setEventBus($bus);
        $this->player->setStartDate(null);
        $this->player->setEndDate(null);
        $this->player->forAllTypes();

        if ($input->getOption('start')) {
            $this->player->setStartDate(new \DateTime($input->getOption('start')));
        }
        if ($input->getOption('end')) {
            $this->player->setEndDate(new \DateTime($input->getOption('end')));
        }
        if ($input->getOption('type')) {
            $this->player->forTypes($input->getOption('type'));
        }

        /** @var ProgressBar $progressBar */
        $progressBar = null;
        $this->player->play(function ($count, $totalCount) use (&$progressBar, $output) {
            if (null == $progressBar) {
                $progressBar = new ProgressBar($output, $totalCount);
                $progressBar->setFormat(' %current%/%max% [%bar%] %percent:3s%% %elapsed:6s%/%estimated:-6s% %memory:6s%');
                $progressBar->start();
            }
            $progressBar->advance();
        });
        $progressBar->finish();
    }
}
