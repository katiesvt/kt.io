<?php

namespace KtIo\WorkerBundle\Services;

use Doctrine\ORM\EntityManager;

use \DateTime;

class TimerService
{
    /**
     * @var integer
     */
    protected $startTime;

    /**
     * Starts a timer.
     */
    public function start()
    {
        $this->startTime = microtime(true);
    }

    /**
     * Ends a timer and returns the time passed since `start()` in seconds.
     * @return float Seconds.
     */
    public function end()
    {
        return microtime(true) - $this->startTime;
    }
}
