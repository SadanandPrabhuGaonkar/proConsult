<?php

namespace A3020\ImageOptimizer\Finder;

use FilterIterator;

class CacheImageFilterIterator extends FilterIterator
{
    /**
     * @return bool
     */
    public function accept()
    {
        $fileInfo = $this->current();

        if ($fileInfo->isDir()) {
            return true;
        }

        $fileName = $fileInfo->getFilename();

        if (preg_match('/\.(?:jpe?g|png|gif)$/', $fileName)) {
            return true;
        }

        return false;
    }
}
