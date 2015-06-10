<?php

namespace AppBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Ratchet\Server\IoServer;
use Ratchet\Http\HttpServer;
use Ratchet\WebSocket\WsServer;
use Ratchet\Wamp\WampServer;

/**
 * @author dkociuba
 */
class StartServerCommand extends ContainerAwareCommand {

    protected function configure() {
        $this
                ->setName('game:server:start')
                ->setDescription('Start the game WebSocket server')
                ->addArgument('port', InputArgument::REQUIRED, 'Port to use');
    }

    protected function execute(InputInterface $input, OutputInterface $output) {
        $ws_manager = $this->getContainer()->get('ws_manager');
        $port = $input->getArgument('port');
        $server = IoServer::factory(
                        new HttpServer(
                            new WsServer(
                                $ws_manager
                            )
                        ), $port
        );
        echo "\nServer run at port $port\n";
        $server->run();
    }

}
