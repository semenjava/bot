<?php
namespace View\Helper\Benchmark;

/**
 * An instance of this class is returned by
 * View_Helper_Benchmark::benchmark().
 * 
 * @package    View
 * @subpackage Helper
 */

/**
 * An instance of this class is returned by
 * View_Helper_Benchmark::benchmark().
 * 
 * @package    View
 * @subpackage Helper
 */
class ViewHelperBenchmarkTimer
{
    /**
     * (Micro-)time that the benchmark was started.
     *
     * @var float
     */
    protected $_start;

    /**
     * Logger instance that will be used to record the time after the benchmark
     * has ended.
     *
     * @var Log_Logger
     */
    protected $_logger;

    /**
     * Message to log after the benchmark has ended
     *
     * @var string
     */
    protected $_message;

    /**
     * Log level to log after the benchmark has ended.
     *
     * @var string|integer
     */
    protected $_level;

    /**
     * Starts a new benchmark.
     *
     * @param string $message           Message to log after the benchmark has
     *                                  ended.
     * @param string|integer $level     Log level to log after the benchmark
     *                                  has ended.
     * @param Log_Logger $logger  Logger instance.
     */
    public function __construct($message, $level = 'info', $logger = null)
    {
        $this->_message = $message;
        $this->_level   = $level;
        $this->_logger  = $logger;
        $this->_start   = microtime(true);
    }

    /**
     * Ends the benchmark and log the result.
     */
    public function end()
    {
        if ($this->_logger) {
            // Compute elapsed time and build message.
            $elapsed = microtime(true) - $this->_start;
            $message = sprintf('%s (%.5f)', $this->_message, $elapsed);

            // Log message (level may be specified as integer or string).
            if (is_integer($this->_level)) {
                $this->_logger->log($message, $this->_level);
            } else {
                $this->_logger->{$this->_level}($message);
            }
        }
    }
}
