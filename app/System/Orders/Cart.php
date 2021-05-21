<?php


namespace BoShop\System\Orders;


use BoShop\System\Products\Product;

class Cart
{

    public const CART_SESSION = "CART_TEMP";

    private static Cart $cart;

    private ?Coupon $coupon = null;
    private array $cartItems;

    public static function getCart(): Cart {
        if(self::$cart ?? null) {
            return self::$cart;
        }

        if($_SESSION[self::CART_SESSION] ?? null) {
            $unserialized = unserialize($_SESSION[self::CART_SESSION], [
                "allowed_classes" => ["Cart"]
            ]);

            self::$cart = $unserialized;
        } else {
            self::$cart = new self();
        }

        return self::$cart;
    }

    public function addItem(Product $product, int $quantity = 1): bool {
        $cartIndex = $this->isInCart($product, true);

        if($cartIndex !== null) {
            /** @var CartItem $cartItem */
            $cartItem = $this->cartItems[$cartIndex];
            $cartItem->addQuantity($quantity);
            $this->cartItems[$cartIndex] = $cartItem;

            return true;
        }

        if(!$product->getProductMutation()->isStockFree($quantity)) {
            return false;
        }

        $cartItem = new CartItem();
        $cartItem->setProduct($product);
        $cartItem->setQuantity($quantity);
        $this->cartItems[] = $cartItem;
        
        return true;
    }

    public function removeItem(Product $product, ?int $quantity = null): bool {
        $cartIndex = $this->isInCart($product, true);

        if($cartIndex !== null) {
            if($quantity === null) {
                unset($this->cartItems[$cartIndex]);
            } else {
                /** @var CartItem $cartItem */
                $cartItem = $this->cartItems[$cartIndex];
                $shouldRemove = $cartItem->decreaseQuantity($quantity);

                if($shouldRemove) {
                    unset($this->cartItems[$cartIndex]);
                } else {
                    $this->cartItems[$cartIndex] = $cartItem;
                }
            }

            return true;
        }

        return false;
    }

    public function getAllItems(): array|null {
        if($this->isCartEmpty())
            return null;

        return $this->cartItems;
    }

    public function getAllItemsAsProducts(): array|null {
        if($this->isCartEmpty())
            return null;

        $products = [];

        /** @var CartItem $cartItem */
        foreach ($this->getAllItems() as $cartItem) {
            $products[] = $cartItem->getProduct();
        }

        return $products;
    }

    public function getAllItemsArProductsAndQuantity(): array|null {
        if($this->isCartEmpty())
            return null;

        $products = [];

        /** @var CartItem $cartItem */
        foreach ($this->getAllItems() as $cartItem) {
            $products[$cartItem->getProduct()->getPrimaryKeyValue()] = $cartItem->getQuantity();
        }

        return $products;
    }

    public function isInCart(Product $product, $getKey = false): bool|int|null {
        /** @var CartItem $cartItem */
        foreach ($this->cartItems as $key => $cartItem) {
            if($cartItem->getProduct()->getPrimaryKeyValue() === $product->getPrimaryKeyValue()) {
                if($getKey) {
                    return $key;
                }

                return true;
            }
        }

        if($getKey) {
            return null;
        }

        return false;
    }

    public function isCartEmpty(): bool {
        return count($this->getAllItems()) > 0;
    }

    public function addCoupon(Coupon $coupon, $forceChange = false): bool {
        if($this->coupon ?? null)
            return false;

        $this->coupon = $coupon;
        return true;
    }

    public function removeCoupon(): bool {
        if($this->coupon ?? null) {
            $this->coupon = null;
            return true;
        }

        return false;
    }

    public function getCartTotalVAT(): ?float {
        if($this->isCartEmpty())
            return null;

        $totalPrice = 0;

        /** @var CartItem $cartItem */
        foreach ($this->getAllItems() as $cartItem) {
            $totalPrice += $cartItem->getProduct()->getProductMutation()->getPriceVAT();
        }

        if($this->coupon ?? null) {
            $totalPrice = $this->coupon->calculate($totalPrice);
        }

        return $totalPrice;
    }

    public function getCartTotal(): ?float {
        if($this->isCartEmpty())
            return null;

        $totalPrice = 0;

        /** @var CartItem $cartItem */
        foreach ($this->getAllItems() as $cartItem) {
            $totalPrice += $cartItem->getProduct()->getProductMutation()->getPrice();
        }

        if($this->coupon ?? null) {
            $totalPrice = $this->coupon->calculate($totalPrice);
        }

        return $totalPrice;
    }

    public function getCoupon(): ?Coupon {
        return $this->coupon;
    }

    public function saveOrder(): bool {
        $order = new Order();

        /** @var CartItem $cartItem */
        foreach ($this->cartItems as $cartItem) {
            $orderItem = new OrderItem();
            $orderItem->setProduct($cartItem->getProduct());
            $orderItem->setQuantity($cartItem->getQuantity());

            $order->orderItems[] = $orderItem;
        }

        $order->save();
        return true;
    }
}