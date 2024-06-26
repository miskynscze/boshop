<?php


namespace BoShop\System\Orders;


use BoShop\Database\DatabaseRow;
use BoShop\System\Products\Product;
use BoShop\System\Products\ProductMutation;
use BoShop\System\Products\ProductVariant;

class OrderItem extends DatabaseRow
{

    protected static string $primaryKey = "order_item_id";
    protected static string $tableName = "order_items";
    protected static array $ignoreVars = [];

    public int $order_item_id;
    public int $order_id;

    public ProductMutation $product;
    public ?ProductVariant $product_variant = null;

    public int $quantity;

    public function getByOrder(Order $order) {
        return $this->getAllByWhere([
            "order_id" => $order->getPrimaryKeyValue()
        ]);
    }

    public function getPriceVAT(): float {
        return $this->product->getPriceVAT() * $this->quantity;
    }

    public function getPrice(): float {
        return $this->product->getPrice() * $this->quantity;
    }

    public function getProduct(): ProductMutation {
        return $this->product;
    }

    public function setProduct(Product $product): void {
        $this->product = $product->getProductMutation();
    }

    public function setQuantity(int $quantity): void {
        $this->quantity = $quantity;
    }

    public function setProductVariant(ProductVariant $productVariant) {
        $this->product_variant = $productVariant;
    }

    public function getProductVariant(): ?ProductVariant {
        return $this->product_variant;
    }
}