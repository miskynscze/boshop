<?php

namespace Modules\Default\Router;

use BoShop\System\Cart\Cart;
use BoShop\System\Products\Product;
use FreeRouter\Attributes\Class\Controller;
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
        $cart = Cart::getCart();

        $product = new Product();
        $product->getById(1);
        $cart->addItem($product, 2);
        $cart->saveCart();

        echo $product->getProductMutation()->getStockQuantity();
    }
}