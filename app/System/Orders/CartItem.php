<?php


namespace BoShop\System\Orders;


use BoShop\System\Products\Product;

class CartItem
{

    public Product $product;
    public int $quantity;

    public function getProduct(): Product {
        return $this->product;
    }

    public function addQuantity(int $quantity): void {
        $this->quantity += $this->quantity;
    }

    public function decreaseQuantity(int $quantity): bool {
        $this->quantity -= $quantity;

        if($this->quantity > 0) {
            return true;
        }

        return false;
    }

    public function getQuantity(): int {
        return $this->quantity;
    }

    public function setQuantity(int $quantity): void {
        $this->quantity = $quantity;
    }

    public function setProduct(Product $product): void {
        $this->product = $product;
    }
}