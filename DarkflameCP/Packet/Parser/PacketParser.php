<?php
namespace DarkflameCP\Packet\Parser;

/**
 * Class PacketParser
 * @package DarkflameCP\Packet\Parser
 *
 * An abstract class to p
 */
abstract class PacketParser {

    // -- Functions

    /**
     * Parses packet data.
     * @param string $data The packet data to parse
     * @return array An array of parsed data
     */
    public abstract function Parse(string $data): array;
}