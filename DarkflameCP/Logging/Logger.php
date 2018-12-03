<?php
namespace DarkflameCP\Logging;

class Logger implements ILogger {

    // -- Private Static Variables

    /** @var array The array of message types to log. */
    private static $logOnly = array();

    /** @var bool Whether or not to save the log to a file. */
    private static $logToFile = true;

    /** @var array The array of message types to refuse to log. */
    private static $noOutput = array();



    // -- Log Methods

    /**
     * Logs an info message.
     * @param string $message Info message to log
     */
    public static function Info(string $message): void {
        self::Log($message, LoggerTypes::INFO);
    }

    /**
     * Logs a fine message.
     * @param string $message Fine message to log
     */
    public static function Fine(string $message): void {
        self::Log($message, LoggerTypes::FINE);
    }

    /**
     * Logs a notice message.
     * @param string $message Notice message to log
     */
    public static function Notice(string $message): void {
        self::Log($message, LoggerTypes::NOTICE);
    }

    /**
     * Logs a debug message.
     * @param string $message Debug message to log
     */
    public static function Debug(string $message): void {
        self::Log($message, LoggerTypes::DEBUG);
    }

    /**
     * Logs a warning message.
     * @param string $message Warning message to log
     */
    public static function Warn(string $message): void {
        self::Log($message, LoggerTypes::WARN);
    }

    /**
     * Logs a error message.
     * @param string $message Error message to log
     */
    public static function Error(string $message): void {
        self::Log($message, LoggerTypes::ERROR);
    }

    /**
     * Logs a fatal message.
     * @param string $message Fatal message to log
     */
    public static function Fatal(string $message): void {
        self::Log($message, LoggerTypes::FATAL);
    }



    // -- Private Static Methods

    /**
     * Logs a message to the console (and the log file, if enabled)
     * @param string $message The message to log
     * @param string $logLevel The log level of the message
     */
    private static function Log(string $message, string $logLevel) {
        if (!empty(self::$logOnly) && !in_array($logLevel, self::$logOnly)) {
            return;
        }

        $writeData = "";
        if (!in_array($logLevel, self::$noOutput)) {
            $writeData = sprintf("%s [%s] > %s%c", date(self::DATE_FORMAT), $logLevel, $message, 10);
            echo $writeData;
        }

        if (self::$logToFile && $writeData != "") {
            self::LogToFile($writeData, $logLevel);
        }
    }

    /**
     * Writes a log string to a file
     * @param string $writeData The data to write to the file
     * @param string $logLevel The log level
     */
    private static function LogToFile(string $writeData, string $logLevel): void {
        $logsDirectory = sprintf("%s/Logs/", __DIR__);

        if (!is_dir($logsDirectory)) {
            mkdir($logsDirectory);
        }

        // Create the log
        $logFile = fopen(sprintf("%s%s.txt", $logsDirectory, $logLevel), "a");

        // Write the data to the log
        fwrite($logFile, sprintf("%s", $writeData));
        fclose($logFile);
    }
}