<?php

namespace KtIo\WebInterfaceBundle\Services;

use KtIo\WebInterfaceBundle\Entity\Url;

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
     * Creates a PUSH ømq socket so that we can act as a ventilator.
     * @param string $socketAddress A ømq-supported path to a socket.
     */
    public function __construct($socketAddress)
    {
        // create a PUSH socket, we are a "ventilator"
        $this->zmqContext = new \ZMQContext();
        $this->zmqSocket = new \ZMQSocket(
            $this->zmqContext, 
            \ZMQ::SOCKET_PUSH
        );
        $this->zmqSocket->connect($socketAddress);
    }

    /**
     * Sends a task to the workers to generate a hash given a URL entity.
     * @param  Url    $url The Url entity to generate for.
     * @return boolean
     */
    public function sendProcessUrlRequest(Url $url)
    {
        // TODO make this send serialized data so workers don't have to make a SELECT query
        $this->zmqSocket->send($url->getId());
    }
}
