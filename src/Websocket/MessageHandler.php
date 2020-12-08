<?php

namespace App\Websocket;

use Exception;
use Ratchet\ConnectionInterface;
use Ratchet\MessageComponentInterface;
//SplObjectStorage agit de la même manière qu'un tableau.
use SplObjectStorage;

//Il y a 4 fonctions que l'interface nous propose de créer.
class MessageHandler implements MessageComponentInterface
{
    protected $connections;

    //La méthode onOpen est appelée lorsqu'une nouvelle connexion est établie avec notre serveur websocket.
    public function onOpen(ConnectionInterface $conn)
    {
        //Le paramètre $ conn qui est passé est cette nouvelle connexion, ajoutons donc cela à notre propriété $connections.
        $this->connections->attach($conn);
    }

    //La méthode onMessage est appelée lorsqu'un message est envoyé à notre serveur websocket.
    public function onMessage(ConnectionInterface $from, $msg)
    {
        //Ici, nous parcourons les connexions. 
        //Tout d'abord, nous vérifions si la connexion sur laquelle nous sommes dans la boucle est celle qui a envoyé le message et l'ignorons si c'est le cas. 
        //Étant donné que la connexion qui a envoyé le message connaît déjà le message, nous n'avons pas besoin de l'envoyer.
        foreach ($this->connections as $connection) {
            //Dans cette fonction, nous allons prendre le message et le transmettre à toutes les autres connexions que nous avons sur notre serveur.
            if ($connection === $from) {
                continue;
            }
            //Nous appelons la méthode send pour transmettre le message.
            $connection->send($msg);
        }
    }

    //La méthode onClose est appelée lorsque quelqu'un se déconnecte de notre serveur.
    public function onClose(ConnectionInterface $conn)
    {
        //Mettons à jour notre méthode onClose pour gérer la suppression de la connexion de notre collection. Pour ce faire, on appele la méthode detach().
        $this->connections->detach($conn);
    }

    //La méthode onError est appelée lorsqu'une erreur se produit.
    public function onError(ConnectionInterface $conn, Exception $e)
    {
        //Gérons ce qui se passe lorsqu'une erreur se produit. 
        //Nous déconnecterons la connexion et la supprimerons de notre collection.
        $this->connections->detach($conn);
        $conn->close();
    }

    public function __construct()
    {
        //On garde une trace de toutes les connexions qui rejoignent notre serveur.
        $this->connections = new SplObjectStorage;
    }
}
