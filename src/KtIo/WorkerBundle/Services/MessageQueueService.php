<?php

namespace KtIo\WorkerBundle\Services;

class MessageQueueService
{

    /**
     * @var \ZMQContext
     */
    protected $zmqContext;
    
    /**
     * @var \ZMQSocket
     */
    protected $zmqSocket;

    /**
     * Creates a PULL socket so we can act as a worker.
     * @param string $socketAddress A ømq-supported path to a socket.
     */
    public function __construct($socketAddress)
    {
        // create a PULL socket, we are a "worker"
        $this->zmqContext = new \ZMQContext();
        $this->zmqSocket = new \ZMQSocket(
            $this->zmqContext, 
            \ZMQ::SOCKET_PULL
        );
        $this->zmqSocket->bind($socketAddress);
    }

    /**
     * Waits until a message appears for us and returns it.
     * @return string The message.
     */
    public function wait()
    {
        return $this->zmqSocket->recv();
    }
}
