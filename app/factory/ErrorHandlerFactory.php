<?php


namespace BoShop\factory;


use Whoops\Handler\PrettyPageHandler;
use Whoops\Run;

class ErrorHandlerFactory extends AbstractFactory
{

    public static function produce()
    {
        $whoops = new Run();
        $whoops->pushHandler(new PrettyPageHandler());
        $whoops->register();
    }
}