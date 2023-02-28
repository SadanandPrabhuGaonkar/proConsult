<?php

namespace A3020\ImageOptimizer;

use InvalidArgumentException;

class Image
{
    protected $pathToImage = '';

    /**
     * @param string $pathToImage
     */
    public function __construct($pathToImage)
    {
        if (! file_exists($pathToImage)) {
            throw new InvalidArgumentException("`{$pathToImage}` does not exist");
        }

        $this->pathToImage = $pathToImage;
    }

    /**
     * @return string
     */
    public function mime()
    {
        if (function_exists('mime_content_type')) {
            return mime_content_type($this->pathToImage);
        }
         
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mime = finfo_file($finfo, $this->pathToImage);
        finfo_close($finfo);

        return $mime;
    }

    /**
     * @return string
     */
    public function path()
    {
        return $this->pathToImage;
    }

    /**
     * @return string
     */
    public function extension()
    {
        $extension = pathinfo($this->pathToImage, PATHINFO_EXTENSION);

        return strtolower($extension);
    }
}
