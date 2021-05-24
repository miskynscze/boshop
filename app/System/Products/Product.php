<?php


namespace BoShop\System\Products;


use BoShop\Database\DatabaseRow;

class Product extends DatabaseRow
{

    protected static string $primaryKey = "product_id";
    protected static string $tableName = "products";
    protected static ?array $callbacksLoad = ["loadProductMutation"];

    public int $product_id;

    public bool $published = false;

    public ProductMutation $product_mutation;

    public function getProductMutation(): ProductMutation {
        return $this->product_mutation;
    }

    public function loadProductMutation(): void {
        if(!isset($this->product_mutation)) {
            $this->product_mutation = new ProductMutation();
            $this->product_mutation->getProductMutation($this);
        }
    }
}