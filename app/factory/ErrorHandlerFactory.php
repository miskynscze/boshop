<?php


namespace BoShop\Factory;


use Whoops\Handler\PrettyPageHandler;
use Whoops\Run;

class ErrorHandlerFactory extends AbstractFactory
{

    public static function produce(): void
    {
        $whoops = new Run();

        if($_ENV["DEBUG"]) {
            $whoops->pushHandler(new PrettyPageHandler());
        } else {
            $whoops->pushHandler(function($e) {
                echo "There was an error";
            });
        }
        $whoops->register();
    }
}