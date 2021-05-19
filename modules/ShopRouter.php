<?php


namespace Modules;


use FreeRouter\Interface\IRouterController;
use Modules\Admin\Router\AdminRouter;
use Modules\Default\Router\DefaultRouter;

class ShopRouter implements IRouterController
{
    public function getRouters(): array
    {
        return [
            new DefaultRouter(),
            new AdminRouter()
        ];
    }
}