<?php


namespace Modules\Admin\Router;


use FreeRouter\Attributes\Class\Controller;
use FreeRouter\Attributes\Class\RequestPrefix;
use FreeRouter\Attributes\Method;
use FreeRouter\Attributes\Request;
use FreeRouter\Attributes\RequestMethod;
use FreeRouter\Interface\IRouter;

#[Controller]
#[RequestPrefix("/admin")]
class AdminRouter implements IRouter
{

    public function before(): void
    {
        // TODO: Implement before() method.
    }

    public function after(): void
    {
        // TODO: Implement after() method.
    }

    #[Method(RequestMethod::GET)]
    #[Request("/")]
    public function index() {
        return "Toto je admin";
    }
}