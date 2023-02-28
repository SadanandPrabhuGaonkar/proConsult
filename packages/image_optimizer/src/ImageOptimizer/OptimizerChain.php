<?php

namespace A3020\ImageOptimizer;

use Psr\Log\LoggerInterface;
use Symfony\Component\Process\Process;

class OptimizerChain
{
    /* @var \A3020\ImageOptimizer\Optimizer[] */
    protected $optimizers = [];

    /** @var \Psr\Log\LoggerInterface */
    protected $logger;

    /** @var int */
    protected $timeout = 60;

    public function __construct()
    {
        $this->useLogger(new DummyLogger());
    }

    /**
     * @return Optimizer[]
     */
    public function getOptimizers()
    {
        return $this->optimizers;
    }

    /**
     * @param Optimizer $optimizer
     * @return $this
     */
    public function addOptimizer($optimizer)
    {
        $this->optimizers[] = $optimizer;

        return $this;
    }

    /**
     * @param array $optimizers
     * @return $this
     */
    public function setOptimizers(array $optimizers)
    {
        $this->optimizers = [];

        foreach ($optimizers as $optimizer) {
            $this->addOptimizer($optimizer);
        }

        return $this;
    }

    /**
     * Set the amount of seconds each separate optimizer may use.
     *
     * @param $timeoutInSeconds
     * @return $this
     */
    public function setTimeout($timeoutInSeconds)
    {
        $this->timeout = $timeoutInSeconds;

        return $this;
    }

    public function useLogger(LoggerInterface $log)
    {
        $this->logger = $log;

        return $this;
    }

    /**
     * @param string $pathToImage Absolute path to the image.
     * @param string $pathToOutput
     */
    public function optimize($pathToImage, $pathToOutput = null)
    {
        if ($pathToOutput) {
            copy($pathToImage, $pathToOutput);

            $pathToImage = $pathToOutput;
        }

        $image = new Image($pathToImage);

        $this->logger->info("Start optimizing {$pathToImage}");

        if (count($this->optimizers) === 0) {
            $this->logger->warning("No optimizers have been configured!");
        }

        foreach ($this->optimizers as $optimizer) {
            $this->applyOptimizer($optimizer, $image);
        }
    }

    /**
     * @param Optimizer $optimizer
     * @param Image $image
     */
    protected function applyOptimizer($optimizer, $image)
    {
        if (! $optimizer->canHandle($image)) {
            return;
        }

        $optimizerClass = get_class($optimizer);

        $this->logger->info("Using optimizer: `{$optimizerClass}`");

        $optimizer->setImagePath($image->path());

        $command = $optimizer->getCommand();

        $this->logger->info("Executing `{$command}`");

        $this->logResult($this->execute($optimizer));
    }

    protected function execute(Optimizer $optimizer)
    {
        if ($optimizer instanceof RemoteOptimizer) {
            return $optimizer->execute();
        }

        $process = new Process($optimizer->getCommand());
        $process
            ->setTimeout($this->timeout)
            ->run();

        return $process;
    }

    /**
     * @param Process|RemoteResult $process
     */
    protected function logResult($process)
    {
        if (! $process->isSuccessful()) {
            $this->logger->error("Process errored with `{$process->getErrorOutput()}`}");

            return;
        }

        $this->logger->info("Process successfully ended with output `{$process->getOutput()}`");
    }
}
