<?php

namespace Modules\Default\Router;

use BoShop\System\Users\User;
use FreeRouter\Attributes\Class\Controller;
use FreeRouter\Attributes\Method;
use FreeRouter\Attributes\Request;
use FreeRouter\Attributes\RequestMethod;
use FreeRouter\Interface\IRouter;
use BoShop\System\Orders\Order;

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
        $user = new User();
        $user->firstname = "Dominik";
        $user->lastname = "Miškovec";
        $user->email = "miskovec.d@gmail.com";
        $user->password = "test123";
        $user->activated = true;
        $user->verified = true;

        $user->save();
    }

    #[Request("/get/{id}")]
    #[Method(RequestMethod::GET)]
    public function getUser($id)
    {
        $user = new User();
        $user->getById($id);

        var_dump($user);
    }

    #[Request("/delete/{id}")]
    #[Method(RequestMethod::GET)]
    public function deleteUser($id)
    {
        $user = new User();

        $user->getById($id);
        $user->delete();
    }

    #[Request("/order")]
    #[Method(RequestMethod::GET)]
    public function createOrder()
    {
        $user = new User();
        $user->getById(1);

        $order = new Order();
        $order->user_id = $user;

        $order->save();
    }

    #[Request("/getOrder/{id}")]
    #[Method(RequestMethod::GET)]
    public function getOrder($id)
    {
        $order = new Order();
        $order->getById($id);

        echo "<pre>";
        var_dump($order);
    }
}