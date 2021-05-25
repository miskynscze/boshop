<?php


namespace BoShop\Tools;

use Exception;

class SimpleTools
{

    public const CHARACTERS_RANDOM = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789!#@&*{}+-[]";

    public static function stdClassToArray(\stdClass $class): array {
        return json_decode(json_encode($class), true);
    }

    public static function arrayTostdClass(array $data): \stdClass {
        $class = new \stdClass();

        foreach ($data as $key => $value) {
            $class->{$key} = $value;
        }

        return $class;
    }

    public static function instanceClass(object $object): object {
        $className = get_class($object);

        return new $className();
    }

    public static function getPropertyType(object $class, string $property): string {
        try {
            $reflection = new \ReflectionProperty($class, $property);

            /** @var \ReflectionNamedType $reflection */
            $reflection = $reflection->getType();
            return $reflection->getName();
        } catch (\ReflectionException $e) {
            throw new Exception($e->getMessage());
        }
    }

    public static function generateRandomString(int $maxLength = 16): string {
        try {
            $lengthNow = 0;
            $randomizedString = "";

            do {
                $randomIndex = random_int(0, strlen(self::CHARACTERS_RANDOM));
                $randomizedString .= self::CHARACTERS_RANDOM[$randomIndex];
                $lengthNow++;
            } while($lengthNow < $maxLength);

            return $randomizedString;
        } catch (Exception $e) {
            throw new Exception("Could not create random string");
        }
    }
}