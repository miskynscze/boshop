<?php


namespace BoShop\System\Categories;


use BoShop\Database\DatabaseRow;

class Category extends DatabaseRow
{

    protected static string $primaryKey = "category_id";
    protected static string $tableName = "categories";

    public int $category_id;

    public Category $parent;

    public string $name;
    public string $shortDescription;
    public string $description;

    public bool $published = false;

    public string $dateCreated = "";
    public string $dateUpdated = "";

    public function getLastParentCategory(): self {
        if($this->parent ?? null) {
            return $this->parent->getLastParentCategory();
        }

        return $this;
    }

    public function getParentCategory(): self {
        return $this->parent;
    }
}