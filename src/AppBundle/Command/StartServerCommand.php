<?php

namespace AppBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Ratchet\Server\IoServer;

/**
 * @author dkociuba
 */
class StartServerCommand extends ContainerAwareCommand {

    protected function configure() {
        $this
                ->setName('game:server:start')
                ->setDescription('Start the game WebSocket server');
    }

    protected function execute(InputInterface $input, OutputInterface $output) {
        $ws_manager = $this->getContainer()->get('ws_manager');
        $server = IoServer::factory($ws_manager, 8080);
        $server->run();
    }

}
