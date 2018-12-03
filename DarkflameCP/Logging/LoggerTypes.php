<?php
namespace DarkflameCP\Logging;

// -- Imports
use DarkflameCP\Types\BasicEnum;

/**
 * Class LoggerTypes
 * @package DarkflameCP\Logging
 *
 * An enum class for the various logger types
 */
abstract class LoggerTypes extends BasicEnum {

    // -- Logger Types

    /** @var string Logger Info */
    const INFO = "Info";

    /** @var string Logger Fine */
    const FINE = "Fine";

    /** @var string Logger Notice */
    const NOTICE = "Notice";

    /** @var string Logger Debug */
    const DEBUG = "Debug";

    /** @var string Logger Warn */
    const WARN = "Warn";

    /** @var string Logger Error */
    const ERROR = "Error";

    /** @var string Logger Fatal */
    const FATAL = "Fatal";
}