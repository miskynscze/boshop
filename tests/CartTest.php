<?php


use BoShop\System\Orders\Cart;
use BoShop\System\Products\Product;
use BoShop\System\Products\ProductMutation;
use BoShop\System\VAT;

final class CartTest extends \PHPUnit\Framework\TestCase
{

    public function __construct(?string $name = null, array $data = [], $dataName = '')
    {
        \BoShop\Factory\EnvironmentFactory::produce();
        \BoShop\Factory\DatabaseFactory::produce();
        parent::__construct($name, $data, $dataName);
    }

    public function testCanBeCartCreated(): void {
        $this->assertInstanceOf(Cart::class, Cart::getCart());
    }

    public function testCanBeItemAdded(): void {
        $cart = Cart::getCart();
        $cart->addItem($this->getTestProduct());

        $this->assertEquals(1, count($cart->getAllItems()));
    }

    public function testCanBeItemRemoved(): void {
        $cart = Cart::getCart();
        $cart->addItem($this->getTestProduct());
        $cart->removeItem($this->getTestProduct());

        $this->assertEquals(null, $cart->getAllItems());
    }

    public function testTotalCart(): void {
        $cart = Cart::getCart();
        $cart->addItem($this->getTestProduct());

        $this->assertEquals(100, $cart->getCartTotal());
    }

    public function testTotalCartVAT(): void {
        $cart = Cart::getCart();
        $cart->addItem($this->getTestProduct());

        $this->assertEquals(121, $cart->getCartTotalVAT());
    }

    private function getTestProduct(): Product {
        $product = new Product();
        $product->product_id = 1;
        $product->published = true;

        $product_mutation = new ProductMutation();
        $product_mutation->quantityStock = 777;
        $product_mutation->published = true;
        $product_mutation->product_id = 1;
        $product_mutation->price = 100;

        $vat = new VAT();
        $vat->name = "VAT 21%";
        $vat->vat = 21;
        $vat->vat_id = 1;

        $product_mutation->vat = $vat;

        $product->product_mutation = $product_mutation;

        return $product;
    }
}