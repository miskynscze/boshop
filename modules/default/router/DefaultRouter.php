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
        echo "Vítejte na domovské stránce";
    }

    #[Request("/product/{?product}")]
    #[Method(RequestMethod::GET)]
    public function productView($product = null) {
        if($product ?? null) {
            $productView = new Product();
            $productView->getById((int)$product);

            echo "<h1>Produkt #{$productView->getPrimaryKeyValue()} - {$productView->getProductMutation()->name}</h1>";
            echo "<p>
                     Description: {$productView->getProductMutation()->description}<br/>
                     Short description: {$productView->getProductMutation()->shortDescription}<br/>
                     Stock: {$productView->getProductMutation()->getStockQuantity()}<br/>
                     Price: {$productView->getProductMutation()->getPriceVAT()}</p>";
        } else {
            header("Location: /");
        }
    }

    #[Request("/cart")]
    #[Method(RequestMethod::GET)]
    public function cartView() {
        $cart = Cart::getCart();

        if($cartItems = $cart->getAllItems()) {
            echo "<pre>";
            var_dump($cart->getAllItems());
        } else {
            echo "V košíku nejsou žádné produkty";
        }
    }
}