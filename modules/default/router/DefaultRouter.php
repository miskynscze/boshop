<?php

namespace Modules\Default\Router;

use BoShop\System\Orders\Coupon;
use BoShop\System\Orders\OrderItem;
use BoShop\System\Users\User;
use BoShop\Tools\MoneyTools;
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

        $items = $order->getOrderItems();

        /** @var OrderItem $item */
        foreach ($items as $item) {
            echo $item->getProduct();
        }
    }

    #[Request("/setOrder/{id}")]
    #[Method(RequestMethod::GET)]
    public function setOrder($id)
    {
        $order = new Order();
        $order->getById($id);

        $coupon = new Coupon();
        $coupon->getById(2);

        $order->setCoupon($coupon);
        $order->save();

        echo "Cena je " . $order->getPriceVAT();
    }
}