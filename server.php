<?php
namespace App;

use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;

class WebSocketServer implements MessageComponentInterface
{
    protected $clients;

    public function __construct()
    {
        $this->clients = new \SplObjectStorage;
    }

    public function onOpen(ConnectionInterface $conn) : void 
    {
        $this->clients->attach($conn);
        print "New connection started on socket server ({$conn->resourceId})\n";        
    }

    public function onMessage(ConnectionInterface $from, $msg) : void
    {
        $count = count($this->clients) - 1;
        sprintf('Connection %d sending message "%s" to %d other connection%s' . "\n"
            , $from->resourceId, $msg, $count, $count == 1 ? '' : 's');

        print "Received Message: {$msg}\n";

        foreach ($this->clients as $client) :
            $client->send($msg);
        endforeach;
    }

    public function onClose(ConnectionInterface $conn) : void 
    {
        $this->clients->detach($conn);
        print "Connection ({$conn->resourceId}) closed\n";
    }

    public function onError(ConnectionInterface $conn, \Exception $e) : void 
    {
        print "Error: ({$e->getMessage()})\n";
        $conn->close();
    }

}


// Using on server
use Ratchet\App;

$app = new App('localhost', 8000, '0.0.0.0');
$app->route('/ws', new WebSocketServer, ['*']);
$app->run();