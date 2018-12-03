<?php
namespace DarkflameCP\Logging;

/**
 * Interface ILogger
 * @package DarkflameCP\Logging
 *
 * An interface for a logger.
 */
interface ILogger {

    // -- Date Types

    /** @var string Date Format */
    public const DATE_FORMAT = "H:i:s";


    // -- Public Methods

    /**
     * Logs an info message.
     * @param string $message Info message to log
     */
    public static function Info(string $message): void;

    /**
     * Logs a fine message.
     * @param string $message Fine message to log
     */
    public static function Fine(string $message): void;

    /**
     * Logs a notice message.
     * @param string $message Notice message to log
     */
    public static function Notice(string $message): void;

    /**
     * Logs a debug message.
     * @param string $message Debug message to log
     */
    public static function Debug(string $message): void;

    /**
     * Logs a warning message.
     * @param string $message Warning message to log
     */
    public static function Warn(string $message): void;

    /**
     * Logs a error message.
     * @param string $message Error message to log
     */
    public static function Error(string $message): void;

    /**
     * Logs a fatal message.
     * @param string $message Fatal message to log
     */
    public static function Fatal(string $message): void;
}