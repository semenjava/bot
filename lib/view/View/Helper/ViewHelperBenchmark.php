<?php
namespace View\Helper;

use View\Helper\Benchmark\ViewHelperBenchmarkTimer;

/**
 * @package    View
 * @subpackage Helper
 */

/**
 * Measures the execution time of a block in a template and reports the result
 * to the log.
 *
 * Example:
 *
 * <code>
 * <?php $bench = $this->benchmark('Notes section') ?>
 * <?php echo $this->expensiveNotesOperation() ?>
 * <?php $bench->end() ?>
 * </code>
 *
 * Will add something like "Notes section (0.34523)" to the log.
 *
 * You may give an optional logger level as the second argument ('debug',
 * 'info', 'warn', 'error').  The default is 'info'.  The level may also be
 * given as a Log::* constant.
 * 
 * @package    View
 * @subpackage Helper
 */
class ViewHelperBenchmark extends ViewHelperBase
{
    /**
     * Starts a new benchmark.
     *
     * @param string $message        Message to log after the benchmark has
     *                               ended.
     * @param string|integer $level  Log level to log after the benchmark has
     *                               ended.
     *
     * @return View_Helper_Benchmark_Timer  A benchmark timer object.
     */
    public function benchmark($message = 'Benchmarking', $level = 'info')
    {
        return new ViewHelperBenchmarkTimer($message, $level,
                                                     $this->_view->logger);
    }
}
