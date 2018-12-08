<?php
namespace DarkflameCP;

// The entry point
use DarkflameCP\Logging\Logger;
use DarkflameCP\Server\Server;
use DarkflameCP\Server\Exception\BindException;

// Set our time zone
date_default_timezone_set('America/New_York');

// Set our error reporting
error_reporting(E_ALL ^ E_STRICT);

// Setup our include paths
set_include_path(get_include_path() . PATH_SEPARATOR . '.');
spl_autoload_extensions('.php');
spl_autoload_register();

// Now attempt to start the login server and run it.
try {
    $loginServer = new Server();
    $loginServer->Start(0, 3724);

    while (true) {
        $loginServer->Update();
    }

} catch (BindException $e) {

    Logger::Error($e->getMessage());
}