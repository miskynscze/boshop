<?php

namespace Modules\Default\Router;

use FreeRouter\Attributes\Class\Controller;
use FreeRouter\Attributes\Class\RequestPrefix;
use FreeRouter\Attributes\Method;
use FreeRouter\Attributes\Request;
use FreeRouter\Attributes\RequestMethod;
use FreeRouter\Interface\IRouter;

#[Controller]
class DefaultRouter implements IRouter
{

    public function before(): void
    {
        // TODO: Implement before() method.
    }

    public function after(): void
    {
        // TODO: Implement after() method.
    }

    #[Request("/")]
    #[Method(RequestMethod::GET)]
    public function index() {
        echo "Toto je default index";
    }
}