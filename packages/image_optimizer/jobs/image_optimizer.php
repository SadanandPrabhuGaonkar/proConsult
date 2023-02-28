<?php

namespace Concrete\Package\ImageOptimizer\Job;

use A3020\ImageOptimizer\Exception\MonthlyLimitReached;
use A3020\ImageOptimizer\Provider\JobServiceProvider;
use A3020\ImageOptimizer\Queue\Create;
use A3020\ImageOptimizer\Queue\Finish;
use A3020\ImageOptimizer\Queue\Process;
use Concrete\Core\Config\Repository\Repository;
use Concrete\Core\Console\ConsoleAwareInterface;
use Concrete\Core\Console\ConsoleAwareTrait;
use Concrete\Core\Job\QueueableJob;
use Concrete\Core\Support\Facade\Application;
use Exception;
use Symfony\Component\Console\Helper\ProgressBar;

final class ImageOptimizer extends QueueableJob implements ConsoleAwareInterface
{
    // This trait is available from version 8.3.0
    use ConsoleAwareTrait;

    /**
     * * Not named 'app' because the parent class might change
     *
     * @var \Concrete\Core\Application\Application
     */
    private $appInstance;

    /** @var ProgressBar */
    protected $progressBar;

    protected $jQueueBatchSize = 5;

    protected $totalBytesSaved = 0;

    protected $totalImagesOptimized = 0;

    public $jSupportsQueue = true;

    public function getJobName()
    {
        return t('Image Optimizer');
    }

    public function getJobDescription()
    {
        return t('Optimizes PNGs, JPGs, SVGs, and GIFs.');
    }

    public function __construct()
    {
        $this->appInstance = Application::getFacadeApplication();

        $config = $this->appInstance->make(Repository::class);
        $this->jQueueBatchSize = (int) $config->get('image_optimizer::settings.batch_size', 5);

        $provider = $this->appInstance->make(JobServiceProvider::class);
        $provider->register();

        // If the job quite unexpectedly before, it'll not continue in the 'start' method
        // Therefore we'll initialize it here, but then without the 2nd parameter (the max number of steps)
        $this->progressBar = new ProgressBar($this->getOutput());
        $this->progressBar->display();

        parent::__construct();
    }

    /**
     * Start the job by creating a queue.
     *
     * @param \ZendQueue\Queue $q
     */
    public function start(\ZendQueue\Queue $q)
    {
        $output = $this->getOutput();

        /** @var Create $createQueue */
        $createQueue = $this->appInstance->make(Create::class);

        try {
            $queue = $createQueue->create($q);

            if ($this->hasConsole()) {
                $this->progressBar = new ProgressBar($output, $queue->count());
                $this->progressBar->display();
            }
        } catch (Exception $e) {
            // We can't report back here, because in a queueable job
            // there are multiple requests. Throwing an exception here is useless.
        }
    }

    /**
     * Process a QueueMessage.
     *
     * @throws MonthlyLimitReached
     *
     * @param \ZendQueue\Message $msg
     */
    public function processQueueItem(\ZendQueue\Message $msg)
    {
        /** @var Process $processQueue */
        $processQueue = $this->appInstance->make(Process::class);
        $processedFile = $processQueue->process($msg);
        if (!$processedFile) {
            return;
        }

        if ($this->hasConsole()) {
            $this->progressBar->advance();
            $this->getOutput()
                ->writeln(' ' .
                    $processedFile->getComputedPath() . ' ' .
                    $processedFile->getFileSizeReduction() . ' bytes'
                );

            $this->totalBytesSaved += $processedFile->getFileSizeReduction();
            $this->totalImagesOptimized++;
        }
    }

    /**
     * Finish processing a queue.
     *
     * @param \ZendQueue\Queue $q
     *
     * @throws \Exception
     *
     * @return string
     */
    public function finish(\ZendQueue\Queue $q)
    {
        /** @var Finish $queue */
        $queue = $this->appInstance->make(Finish::class);

        try {
            $queue->precheck();
        } catch (MonthlyLimitReached $e) {
            return t('Stopped because the monthly limit is reached.');
        } catch (Exception $e) {
            if ($e instanceof \Tinify\Exception) {
                throw new Exception(t('TinyPNG error: %s', $e->getMessage()));
            }

            throw $e;
        }

        if (!$this->hasConsole()) {
            return $queue->finish($q);
        }

        $this->progressBar->clear();

        $numberHelper = $this->appInstance->make('helper/number');

        return
            sprintf('The job ran successfully. Number of optimized images: %s.',
                $this->totalImagesOptimized
            ) . ' '.
            sprintf('Total size gained: %s (%s).',
                $this->totalBytesSaved,
                $numberHelper->formatSize($this->totalBytesSaved)
            );
    }
}
