<?php


namespace BoShop\System\Products;


use BoShop\Database\DatabaseRow;

class ProductVariant extends DatabaseRow
{

    public const COLOR = 1;
    public const SIZE = 2;

    protected static string $primaryKey = "product_variant_id";
    protected static string $tableName = "products_variants";

    public int $product_variant_id;

    public ProductMutation $product_mutation;
    public int $stock = 1;

    public int $variantType = self::COLOR;
    public string $value;

    public bool $infinity = false;
    public bool $published = false;
}