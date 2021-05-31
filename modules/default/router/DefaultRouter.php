<?php

namespace Modules\Default\Router;

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

            echo "<pre>";
            var_dump($productView);
        } else {
            header("Location: /");
        }
    }

    #[Request("/paymentMollie")]
    #[Method(RequestMethod::GET)]
    public function indexPayment() {
        echo "Test";
    }

    #[Request("/webhook")]
    #[Method(RequestMethod::GET)]
    public function webhookPayment() {

    }
}