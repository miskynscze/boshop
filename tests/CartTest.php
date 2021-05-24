<?php


use BoShop\System\Orders\Cart;
use BoShop\System\Orders\Coupon;
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
        self::assertInstanceOf(Cart::class, Cart::getCart());
    }

    public function testIsCartEmpty(): void {
        self::assertEquals(true, Cart::getCart()->isCartEmpty());
    }

    public function testCanBeItemAdded(): void {
        $cart = Cart::getCart();
        $cart->addItem($this->getTestProduct());

        self::assertEquals(1, count($cart->getAllItems()));
    }

    public function testCanBeItemRemoved(): void {
        $cart = Cart::getCart();
        $cart->addItem($this->getTestProduct());
        $cart->removeItem($this->getTestProduct());

        self::assertEquals(null, $cart->getAllItems());
    }

    public function testTotalCart(): void {
        $cart = Cart::getCart();
        $cart->addItem($this->getTestProduct());

        self::assertEquals(100, $cart->getCartTotal());
    }

    public function testTotalCartVAT(): void {
        $cart = Cart::getCart();
        $cart->addItem($this->getTestProduct());

        self::assertEquals(121, $cart->getCartTotalVAT());
    }

    public function testSaveCartSession(): void {
        $cart = Cart::getCart();
        $cart->addItem($this->getTestProduct());
        $cart->saveCart();

        Cart::deleteTempCart();
        $cartLoaded = Cart::getCart();
        self::assertIsArray($cartLoaded->getAllItems());

        Cart::deleteSessionCart();
    }

    public function testCartCoupon(): void {
        $cart = Cart::getCart();
        $cart->addItem($this->getTestProduct());
        $cart->addCoupon($this->getTestCoupon());
        self::assertEquals(101, $cart->getCartTotalVAT());

        $cart->saveCart();
    }

    public function testCartCouponRemove(): void {
        $cart = Cart::getCart();
        $cart->removeCoupon();

        self::assertEquals(121, $cart->getCartTotalVAT());

        //Clear cart after tests
        Cart::deleteTempCart();
        Cart::deleteSessionCart();
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

    private function getTestCoupon(): Coupon {
        $coupon = new Coupon();
        $coupon->name = "Kupon 20";
        $coupon->type = Coupon::VALUE;
        $coupon->value = 20;

        return $coupon;
    }
}