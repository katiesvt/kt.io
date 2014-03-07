<?php
namespace KtIo\WorkerBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

use Ktio\WorkerBundle\Service\MessageQueueService;
use Ktio\WorkerBundle\Service\UrlService;
use Ktio\WorkerBundle\Service\TimerService;

class WorkerCommand extends ContainerAwareCommand
{

    /**
     * @var MessageQueueService
     */
    protected $messageQueueService;

    /**
     * @var UrlService
     */
    protected $urlService;

    /**
     * @var TimerService
     */
    protected $timerService;

    protected function configure()
    {
        $this
            ->setName('ktio:worker')
            ->setDescription('Spawn a worker thread');
    }

    /**
     * Runs the command, waiting for and responding to requests from the web interface.
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln("Spawning worker thread...");
        $this->loadServices();
        $output->writeln("Listening for tasks...");

        while (true)
        {
            $message = $this->messageQueueService->wait();
            $output->writeln("Message received to process URL id $message...");

            $this->timerService->start();
            $this->urlService->createShortUrl($this->urlService->findByIdString($message));
            // TODO send a response back to the ventilator to allow synchronous requests
            $timeElapsed = $this->timerService->end();

            $output->writeln("URL generated in ${timeElapsed} s.");
        }

    }

    /**
     * Helper function to load our services.
     */
    private function loadServices()
    {
        $this->messageQueueService = $this->getContainer()->get('ktio_worker.message_queue_service');
        $this->urlService = $this->getContainer()->get('ktio_worker.url_service');
        $this->timerService = $this->getContainer()->get('ktio_worker.timer_service');
    }

}
