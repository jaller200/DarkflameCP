<?php
namespace DarkflameCP\Server;

// -- Imports
use DarkflameCP\Logging\Logger;
use DarkflameCP\Packet\Packet;
use DarkflameCP\Packet\PacketType;

/**
 * Class LoginServer
 * @package DarkflameCP\Server
 *
 * A server specifically to allow one to login to the game.
 */
final class LoginServer extends Server {

    // -- Private Variables

    private $XML_HANDLERS = array(
        "policy-file-request" => "HandlePolicyRequest"
    );



    // -- Constructor

    /**
     * LoginServer constructor.
     */
    public function __construct() {
        parent::__construct();
    }



    // -- Packet Handlers

    /**
     * Handles a packet.
     * @param resource $socket The socket resource
     * @param Packet $packet The packet
     */
    protected function HandlePacket($socket, Packet $packet) {

        // Login only needs to handle XML packets.
        if ($packet->getType() === PacketType::XML) {

            $rootTag = $packet->getData()[0];
            if (isset($this->XML_HANDLERS[$rootTag])) {

                // Get the handler name
                $handler = $this->XML_HANDLERS[$rootTag];
                if (method_exists($this, $handler)) {

                    // Call the function method
                    call_user_func(array($this, $handler), $socket, $packet);
                }
            }
        }
    }



    // -- Packet Handler Methods

    /**
     * Handles a policy request.
     * @param resource $socket The socket resource
     * @param Packet $packet The packet data
     */
    private function HandlePolicyRequest($socket, Packet $packet) {

        // Send data
        $response = "<cross-domain-policy><allow-access-from domain='*' to-ports='{$this->port}' /></cross-domain-policy>";
        $this->SendData($response, $socket);
    }
}