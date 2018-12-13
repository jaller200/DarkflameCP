<?php
namespace DarkflameCP\Packet\Parser\Parsers;

use DarkflameCP\Logging\Logger;
use DarkflameCP\Packet\Parser\PacketParser;

/**
 * Class PacketParserXML
 * @package DarkflameCP\Packet\Parser\Parsers
 *
 * A packet parsers for XML data.
 */
class PacketParserXML extends PacketParser {

    // -- Constructor

    /**
     * PacketParserXML constructor.
     */
    public function __construct() { }



    // -- Parse Methods

    /**
     * Parses XML data.
     * @param string $data The data to parse
     * @return array The parsed XML data
     */
    public function Parse(string $data): array {

        $xml = simplexml_load_string($data, "SimpleXMLElement", LIBXML_NOCDATA);
        $root = $xml->getName();

        $json = json_encode($xml);
        $array = json_decode($json, true);

        return [$root, $array];
    }
}