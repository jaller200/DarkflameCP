<?php
namespace DarkflameCP\Server;

// -- Imports
use DarkflameCP\Logging\Logger;
use DarkflameCP\Packet\Packet;
use DarkflameCP\Packet\Parser\PacketParserIO;
use DarkflameCP\Server\Exception\BindException;

/**
 * Class Server
 * @package DarkflameCP\Server
 *
 * An abstract class that describes a server.
 */
abstract class Server {

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
    public function __construct() {

        // Initialize any necessary data here.
        PacketParserIO::Initialize();
    }




    // -- Packet Handler Methods

    /**
     * Handles a received packet.
     * @param resource $socket The client socket
     * @param Packet $packet The packet
     */
    protected abstract function HandlePacket($socket, Packet $packet);




    // -- Listen Method

    /**
     * Starts the server on a specific port.
     * @param int $address The IP address
     * @param int $port The port
     * @param int $backlog The backlog of packets we keep
     * @throws BindException Thrown if the server cannot bind to a port
     */
    public function Start(int $address, int $port, int $backlog = 25): void {

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
    public function Update(): void {

        // Get the changed sockets
        $sockets = array_merge(array($this->masterSocket), $this->sockets);

        // Check to see what has changed in the sockets (and if there are any new sockets.
        $changed = socket_select($sockets, $write, $except, 1);

        // If nothing changed, exit
        if ($changed === 0) {
            return;
        }

        // If the master socket is in the array, we need to accept a new client connection
        if (in_array($this->masterSocket, $sockets)) {

            // Accept our new client socket
            $clientSocket = $this->AcceptClientSocket();

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

                // Handle socket packet
                $dataArray = explode("\0", $buffer);
                array_pop($dataArray);

                foreach ($dataArray as $data) {

                    Logger::Notice("Received Data: $data");

                    // Create our packet
                    $packet = new Packet($socket, $data);

                    // Handle our packet
                    $this->HandlePacket($socket, $packet);
                }
            }
        }
    }



    // -- Packet Methods

    /**
     * Sends data to a socket.
     * @param string $data The data to send
     * @param resource $socket The socket to send the data to
     * @param boolean $broadcast Whether or not to broadcast to all sockets (excluding the one passed)
     * @return int The number of bytes written
     */
    public function SendData(string $data, $socket, bool $broadcast = false): int {
        $data .= "\0";
        $bytesWritten = 0;

        // If we are broadcasting, send to all clients
        if ($broadcast) {

            foreach ($this->sockets as $curSocket) {
                if (is_resource($socket) && $socket === $curSocket) continue;

                $bytesWritten = socket_send($curSocket, $data, strlen($data), 0);
            }
        } else {
            $bytesWritten = socket_send($socket, $data, strlen($data), 0);
        }

        return $bytesWritten;
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
    private function RemoveClientSocket($socket): void {

        $index = array_search($socket, $this->sockets);
        unset($this->sockets[$index]);

        // If the socket is a resource, close the socket
        if (is_resource($socket)) {
            socket_close($socket);
        }
    }
}

ob_implicit_flush();