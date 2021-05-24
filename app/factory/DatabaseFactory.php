<?php


namespace BoShop\Factory;


use BoShop\Database\Database;

class DatabaseFactory extends AbstractFactory
{

    private static Database $database;

    public static function produce(): Database
    {
        if(self::$database ?? null)
            return self::$database;

        self::$database = new Database();
        self::$database->connect($_ENV["DB_HOST"], $_ENV["DB_USER"], $_ENV["DB_PASS"], $_ENV["DB_NAME"]);

        return self::$database;
    }
}