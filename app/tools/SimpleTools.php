<?php


namespace BoShop\Tools;


use _HumbugBoxa991b62ce91e\Nette\Neon\Exception;

class SimpleTools
{

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

    public static function getPropertyType(object $class, string $property) {
        try {
            $reflection = new \ReflectionProperty($class, $property);

            /** @var \ReflectionNamedType $reflection */
            $reflection = $reflection->getType();
            return $reflection->getName();
        } catch (\ReflectionException $e) {
            throw new Exception($e->getMessage());
        }
    }
}