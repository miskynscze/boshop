<?php


namespace BoShop\Factory;


use FreeRouter\Interface\IRouterController;
use FreeRouter\RouterConfig;
use FreeRouter\RouterWrapper;
use Modules\ShopRouter;

class RouterFactory extends AbstractFactory
{

    private RouterWrapper $router;

    public static function produce()
    {
        $wrapper = new RouterWrapper();
        $wrapper->config(new RouterConfig());

        $factory = new self();
        $factory->setRouter($wrapper);

        //Adding default router (modules like admin etc...)
        $factory->addRouter(new ShopRouter());
    }

    public function setRouter(RouterWrapper $router) {
        $this->router = $router;
    }

    public function addRouter(IRouterController $router) {
        $this->router->run($router);
    }
}