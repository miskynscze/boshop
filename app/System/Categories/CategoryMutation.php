
<?php


namespace BoShop\System\Categories;


use BoShop\Database\DatabaseRow;

class CategoryMutation extends DatabaseRow
{

    protected static string $primaryKey = "category_mutation_id";
    protected static string $tableName = "categories_mutations";

    public int $category_mutation_id;
}