<?php


namespace BoShop\System\Products;


use BoShop\Database\DatabaseRow;
use BoShop\System\Mutation;
use BoShop\System\VAT;

class ProductMutation extends DatabaseRow
{

    public const IN_STOCK = 1;
    public const OUT_OF_STOCK = 2;
    public const ON_DEMAND = 3;
    public const COMING_SOON = 4;

    protected static string $primaryKey = "product_mutation_id";
    protected static string $tableName = "products_mutations";

    public int $product_mutation_id;
    public Product $product_id;

    public string $name;
    public string $description;
    public string $shortDescription;

    public string $alias;

    public float $buyPrice;
    public float $price;

    public VAT $vat;
    public Mutation $mutation;

    public int $quantityStock = 0;
    public int $stockStatus = self::IN_STOCK;

    public bool $published = false;
}