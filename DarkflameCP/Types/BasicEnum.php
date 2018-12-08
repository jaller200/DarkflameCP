<?php
namespace DarkflameCP\Types;

// Imports
use \ReflectionClass;
use \ReflectionException;

/**
 * Class BasicEnum
 * @package DarkflameCP\Types
 *
 * A class that represents an enum type
 */
abstract class BasicEnum {

    // -- Private Static Variables

    /** @var array The cache array. */
    private static $constCacheArray = NULL;



    // -- Public Static Methods

    /**
     * Checks to see if a given key is a valid enum name.
     * @param string $name The name of the key to check
     * @param bool $strict Whether or not we want to use strict checking (case must match)
     * @return bool Whether or not the key exists
     */
    public static function IsValidName(string $name, bool $strict = false): bool {
        $constants = self::GetConstants();

        if ($strict) {
            return array_key_exists($name, $constants);
        }

        $keys = array_map('strtolower', array_keys($constants));
        return in_array(strtolower($name), $keys);
    }

    /**
     * Checks if a value is a valid enum value.
     * @param object $value The value to check
     * @param bool $strict Whether or not to use strict case-checking
     * @return bool Whether or not the value is a valid value
     */
    public static function IsValidValue(object $value, bool $strict = true): bool {
        $values = array_values(self::GetConstants());

        return in_array($value, $values, $strict);
    }



    // -- Private Static Methods

    /**
     * Returns an array of class constants.
     * @return array The array of constants
     */
    private static function GetConstants(): array {
        if (self::$constCacheArray == NULL) {
            self::$constCacheArray = [];
        }

        $calledClass = get_called_class();
        if (!array_key_exists($calledClass, self::$constCacheArray)) {
            try {
                $reflect = new ReflectionClass($calledClass);
                self::$constCacheArray[$calledClass] = $reflect->getConstants();

            } catch (ReflectionException $e) {
                return array();
            }
        }

        return self::$constCacheArray[$calledClass];
    }
}