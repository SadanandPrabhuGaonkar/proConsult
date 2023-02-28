<?php

namespace A3020\ImageOptimizer;

interface Optimizer
{
    /**
     * Returns the name of the binary to be executed.
     *
     * @return string
     */
    public function binaryName();

    /**
     * Determines if the given image can be handled by the optimizer.
     *
     * @param \A3020\ImageOptimizer\Image $image
     *
     * @return bool
     */
    public function canHandle($image);

    /**
     * Set the path to the image that should be optimized.
     *
     * @param string $imagePath
     *
     * @return $this
     */
    public function setImagePath($imagePath);

    /**
     * Set the options the optimizer should use.
     *
     * @param array $options
     *
     * @return $this
     */
    public function setOptions(array $options = []);

    /**
     * Get the command that should be executed.
     *
     * @return string
     */
    public function getCommand();
}
