<?php
namespace DarkflameCP\Server;

// -- Imports
use DarkflameCP\Logging\Logger;
use DarkflameCP\Server\Exception\BindException;

/**
 * Class Server
 * @package DarkflameCP\Server
 *
 * An abstract class that describes a server.
 */
class Server {

    // -- Private Variables

    /** @var resource The master socket resource. */
    protected $masterSocket;

    /** @var number The port that this server is running on */
    protected $port;

    /** @var resource[] An array of socket data. */
    protected $sockets = array();



    // -- Constructor

    /**
     * Server constructor.
     */
    public function __construct() { }



    // -- Listen Method

    /**
     * Starts the server on a specific port.
     * @param int $address The IP address
     * @param int $port The port
     * @param int $backlog The backlog of packets we keep
     * @throws BindException Thrown if the server cannot bind to a port
     */
    public function Start(int $address, int $port, int $backlog = 25) {

        // Create the master socket
        $socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
        socket_set_option($socket, SOL_SOCKET, SO_REUSEADDR, 1);
        socket_set_nonblock($socket);

        // Now attempt to bind our socket
        $success = socket_bind($socket, $address, $port);
        if (!$success) {
            throw new BindException("Error binding to port '$port' on address '$address'");
        }

        // Listen on the socket
        socket_listen($socket, $backlog);

        // Save our server settings
        $this->port = $port;
        $this->masterSocket = $socket;

        Logger::Notice("Successfully started server on port '$port' (address: '$address')");
    }

    /**
     * Updates the server.
     */
    public function Update() {

        // Get the changed sockets
        $sockets = array_merge(array($this->masterSocket), $this->sockets);

        // Check to see what has changed in the sockets (and if there are any new sockets.
        $changed = socket_select($sockets, $write, $except, 1);

        // If nothing changed, exit
        if ($changed === 0) {
            return false;
        }

        // If the master socket is in the array, we need to accept a new client connection
        if (in_array($this->masterSocket, $sockets)) {

            $clientSocket = $this->AcceptClientSocket();

            // TODO: Handle the new client socket

            // Remove the master socket from the array before we iterate through it
            unset($sockets[0]);
        }

        // Now iterate through the changed sockets and handle their status
        foreach ($sockets as $socket) {

            // Fetch a packet from the socket
            $data = socket_recv($socket, $buffer, 8192, 0);

            // If there is no data, remove the connection.
            // Otherwise, handle the received packet
            if ($data == null) {
                $this->RemoveClientSocket($socket);
            } else {
                // TODO: Handle socket packet

                Logger::Notice("Received Data: $buffer");
            }
        }
    }



    // -- Helper Methods

    /**
     * Creates a new master socket.
     * @return resource The created master socket
     */
    private function AcceptClientSocket() {

        // Accept the new client socket and set options
        $clientSocket = socket_accept($this->masterSocket);
        socket_set_option($clientSocket, SOL_SOCKET, SO_REUSEADDR, 1);
        socket_set_nonblock($clientSocket);

        // Add the client socket to the array
        $this->sockets[] = $clientSocket;
        Logger::Notice("Accepted New Client Connection: $clientSocket");

        return $clientSocket;
    }

    /**
     * Removes a client socket.
     * @param resource $socket The client socket to remove
     */
    private function RemoveClientSocket($socket) {

        $index = array_search($socket, $this->sockets);
        unset($this->sockets[$index]);

        // If the socket is a resource, close the socket
        if (is_resource($socket)) {
            socket_close($socket);
        }
    }
}

ob_implicit_flush();