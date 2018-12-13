<?php
namespace DarkflameCP\Packet;

// -- Imports
use DarkflameCP\Types\BasicEnum;

/**
 * Class PacketType
 * @package DarkflameCP\Packet
 *
 * An enum for various packet types.
 */
abstract class PacketType extends BasicEnum {

    // -- Packet Types

    /** @var string Unknown Packet Type */
    const UNKNOWN = "unknown";

    /** @var string Club Penguin Packet Type */
    const CP = "cp";

    /** @var string XML Packet Type */
    const XML = "xml";
}