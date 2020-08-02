<?php


namespace App\Websocket;


use Ratchet\ConnectionInterface;
use Ratchet\MessageComponentInterface;

class MessageHandler implements MessageComponentInterface
{
    protected $connections;

    public function __construct()
    {
        $this->connections = new \SplObjectStorage();
    }

    /**
     * @param ConnectionInterface $conn
     */
    public function onOpen(ConnectionInterface $conn): void
    {
        $this->connections->attach($conn);
    }

    /**
     * @param ConnectionInterface $conn
     */
    public function onClose(ConnectionInterface $conn): void
    {
        $this->connections->detach($conn);
    }

    /**
     * @param ConnectionInterface $conn
     * @param \Exception $e
     */
    public function onError(ConnectionInterface $conn, \Exception $e): void
    {
        $this->connections->detach($conn);
        $conn->close();
    }

    /**
     * @param ConnectionInterface $from
     * @param string $msg
     */
    public function onMessage(ConnectionInterface $from, $msg): void
    {
        foreach ($this->connections as $connection){
            if($connection === $from){
                continue;
            }
            $connection->send($msg);
        }
    }
}
