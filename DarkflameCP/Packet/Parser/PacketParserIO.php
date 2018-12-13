<?php
namespace DarkflameCP\Packet\Parser;

use DarkflameCP\Logging\Logger;
use DarkflameCP\Packet\PacketType;
use DarkflameCP\Packet\Parser\Parsers\PacketParserXML;

/**
 * Class PacketParserIO
 * @package DarkflameCP\Packet\Parser
 *
 * Packet Parser utility methods.
 */
class PacketParserIO {

    // -- Private Variables

    /** @var PacketParser[] An array of various packet parsers. */
    private static $parsers;



    // -- Initialization

    /**
     * Initializes all packet parsers in the ./Parsers array.
     */
    public static function Initialize() {

        PacketParserIO::$parsers = array();

        // Register parsers here
        PacketParserIO::RegisterParser(new PacketParserXML(), PacketType::XML);
    }



    // -- Parser Methods

    /**
     * Parses packet data and returns an array of data
     * @param string $data The packet data
     * @param string $packetType The packet type
     * @return array An array of parsed data.
     */
    public static function ParseData(string $data, string $parserType): array {

        // First, find the packet parser by parser type
        $parser = PacketParserIO::$parsers[$parserType];
        if ($parser !== null) {
            return $parser->Parse($data);
        }

        return array();
    }



    // -- Private Helper Methods

    /**
     * Registers a packet parser.
     * @param PacketParser $parser The parser
     * @param string $parserType The parser type
     */
    private static function RegisterParser(PacketParser $parser, string $parserType): void {

        if (PacketParserIO::$parsers[$parserType] != null) {
            Logger::Warn("Parser already exists for type '$parserType'. Overwriting...");
        }

        PacketParserIO::$parsers[$parserType] = $parser;
    }
}