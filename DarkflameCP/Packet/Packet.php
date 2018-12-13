<?php
namespace DarkflameCP\Packet;

use DarkflameCP\Packet\Parser\PacketParserIO;

/**
 * Class Packet
 * @package DarkflameCP\Packet
 *
 * A simple wrapper for a packet of data.
 */
class Packet {

    // -- Private Variables

    /** @var array The packet data. */
    private $data;

    /** @var number The packet data length. */
    private $length;

    /** @var string The raw packet data. */
    private $rawData;

    /** @var resource The socket the packet was sent from. */
    private $socket;

    /** @var PacketType The packet type */
    private $type;



    // -- Constructor

    /**
     * Packet constructor.
     * @param resource $socket The socket the packet was received on
     * @param string $data The packet data
     */
    public function __construct($socket, string $data) {

        $this->length = $data.$this->length;
        $this->rawData = $data;
        $this->socket = $socket;

        // Parse the packet here
        $this->ParsePacket($data);
    }



    // -- Parsing

    /**
     * Parses the packet data.
     * @param string $data The packet data
     */
    private function ParsePacket(string $data): void {

        // First, determine the packet type
        $this->type = $this->ParsePacketType($data);
        switch ($this->type) {
            case PacketType::XML: {

                $this->data = PacketParserIO::ParseData($data, PacketType::XML);
                break;
            }

            default: break;
        }
    }

    /**
     * Parses the packet data type.
     * @param string $data The packet data
     * @return string The packet type
     */
    private function ParsePacketType(string $data): string {

        $firstChar = substr($data, 0, 1);
        switch ($firstChar) {
            case '<': {
                return PacketType::XML;
            }

            default: {
                return PacketType::UNKNOWN;
            }
        }
    }



    // -- Getters

    /**
     * Returns the parsed packet data.
     * @return array The packet data
     */
    public function getData(): array {
        return $this->data;
    }

    /**
     * Returns the packet length.
     * @return int The packet length
     */
    public function getLength(): int {
        return $this->length;
    }

    /**
     * Returns the raw data.
     * @return string The raw data
     */
    public function getRawData(): string {
        return $this->rawData;
    }

    /**
     * Returns the packet socket.
     * @return resource The packet socket.
     */
    public function getSocket() {
        return $this->socket;
    }

    /**
     * Returns the packet type.
     * @return string The packet type
     */
    public function getType(): string {
        return $this->type;
    }
}