<?php

namespace App\Command;

use Ratchet\Http\HttpServer;
use Ratchet\Server\IoServer;
use Ratchet\WebSocket\WsServer;
use App\Websocket\MessageHandler;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class WebsocketServerCommand extends Command
{
    //Nom de la commande -> php bin/console run:websocket-server
    protected static $defaultName = "run:websocket-server";

    //La fonction execute prend un paramètre $input et $output,
    //qui sont naturellement utilisés pour gérer n'importe quelle entrée et afficher n'importe quelle sortie.
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        //Le code pour créer et exécuter le serveur
        //On initiale le port à 3001
        $port = 3001;
        $output->writeln("Le serveur tourne sur le " . $port);
        $server = IoServer::factory(
            new HttpServer(
                new WsServer(
                    new MessageHandler()
                )
            ),
            $port
        );
        $server->run();

        //À partir de Symfony 5, la fonction d'exécution des commandes Symfony doit renvoyer un int. 
        //Il n'y a pas de véritable raisonnement derrière le renvoi de 0 dans notre cas, sauf pour éviter les erreurs.
        return 0;
    }
}
