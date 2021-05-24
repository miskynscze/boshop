<?php


namespace BoShop\System\Cart;


use BoShop\Database\DatabaseRow;
use BoShop\System\Products\Product;
use BoShop\System\Products\ProductMutation;
use BoShop\System\Products\ProductVariant;

class CartVirtualItem extends DatabaseRow
{

    protected static string $primaryKey = "cart_virtual_item_id";
    protected static string $tableName = "cart_virtual_items";

    public int $cart_virtual_item_id;

    public Product $product;
    public ?ProductVariant $product_variant;
    public int $quantity;

    public string $cartIdentifier;

    public ?string $dateUpdated;
    public ?string $dateCreated;

    public function setProduct(Product $product): void {
        $this->product = $product;
    }

    public function setQuantity(int $quantity): void {
        $this->quantity = $quantity;
    }

    public function setCartIdentifier(string $cartIdentifier): void {
        $this->cartIdentifier = $cartIdentifier;
    }

    public function getByProduct(ProductMutation $product, ProductVariant $variant): void {
        $whereCondition = [
            "product" => $product->getPrimaryKeyValue()
        ];

        if(isset($variant->product_variant_id)) {
            $whereCondition["product_variant"] = $variant->getPrimaryKeyValue();
        }

        $this->getByWhere($whereCondition);
    }

    public function getByCartIdentifier(string $cartIdentifier): void {
        $this->getByWhere([
            "cartIdentifier" => $cartIdentifier
        ]);
    }
}