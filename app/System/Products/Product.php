<?php


namespace BoShop\System\Products;


use BoShop\Database\DatabaseRow;

class Product extends DatabaseRow
{

    protected static string $primaryKey = "product_id";
    protected static string $tableName = "products";

    public int $product_id;
}