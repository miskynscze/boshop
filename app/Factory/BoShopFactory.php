<?php


namespace BoShop\Factory;


class BoShopFactory extends AbstractFactory
{

    public static function produce()
    {
        \BoShop\factory\EnvironmentFactory::produce();
        \BoShop\factory\ErrorHandlerFactory::produce();
        \BoShop\Factory\RouterFactory::produce();
    }
}