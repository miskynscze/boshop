<?php


namespace BoShop\System\Cart;


use BoShop\System\Products\Product;

class CartItem
{

    public Product $product;
    public int $quantity;

    private ?CartVirtualItem $cartVirtualItem = null;

    public function getProduct(): Product {
        return $this->product;
    }

    public function addQuantity(int $quantity): void {
        $this->quantity += $quantity;

        $this->updateQuantityVirtualItem($this->quantity);
    }

    public function decreaseQuantity(int $quantity): bool {
        $this->quantity -= $quantity;
        $this->updateQuantityVirtualItem($this->quantity);

        return $this->quantity > 0;
    }

    public function getQuantity(): int {
        return $this->quantity;
    }

    public function setQuantity(int $quantity): void {
        $this->quantity = $quantity;

        $this->updateQuantityVirtualItem($this->quantity);
    }

    public function setProduct(Product $product): void {
        $this->product = $product;
    }

    public function createVirtualReservation(): void {
        $cartVirtualItem = new CartVirtualItem();
        $cartVirtualItem->setProduct($this->getProduct());
        $cartVirtualItem->setQuantity($this->getQuantity());
        $cartVirtualItem->setCartIdentifier(Cart::getCart()->getCartIdentifier());

        $cartVirtualItem->save();

        $this->cartVirtualItem = $cartVirtualItem;
    }

    public function deleteVirtualReservation(): bool {
        if($this->cartVirtualItem ?? null) {
            $deleted = $this->cartVirtualItem->delete();
            $this->cartVirtualItem = null;

            return $deleted;
        }

        return false;
    }

    private function updateQuantityVirtualItem(int $quantity): void {
        if($this->cartVirtualItem ?? null) {
            $this->cartVirtualItem->setQuantity($quantity);
            $this->cartVirtualItem->save();
        }
    }
}