<?php

namespace A3020\ImageOptimizer\Statistics;

use Concrete\Core\Application\ApplicationAwareInterface;
use Concrete\Core\Application\ApplicationAwareTrait;

class Month implements ApplicationAwareInterface
{
    use ApplicationAwareTrait;

    /**
     * Return the number of optimizations performed this month.
     *
     * @return int
     */
    public function total()
    {
        $db = $this->app->make('database')->connection();

        return (int) $db->fetchColumn('
          SELECT COUNT(1) FROM ImageOptimizerProcessedFiles 
          WHERE UNIX_TIMESTAMP(processedAt) >= UNIX_TIMESTAMP(LAST_DAY(CURDATE()) + INTERVAL 1 DAY - INTERVAL 1 MONTH)
          AND UNIX_TIMESTAMP(processedAt) < UNIX_TIMESTAMP(LAST_DAY(CURDATE()) + INTERVAL 1 DAY)
        ');
    }
}
