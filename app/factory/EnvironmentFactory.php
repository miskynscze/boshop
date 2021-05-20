<?php


namespace BoShop\Factory;


use Dotenv\Dotenv;

class EnvironmentFactory extends AbstractFactory
{

    public static function produce()
    {
        $dotenv = Dotenv::createImmutable(__DIR__ . "/../../");
        $dotenv->load();
    }
}