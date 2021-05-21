<?php


namespace BoShop\System\Products;


use BoShop\Database\DatabaseRow;
use BoShop\System\Mutation;
use BoShop\System\VAT;
use BoShop\Tools\ProjectTools;

class ProductMutation extends DatabaseRow
{

    public const IN_STOCK = 1;
    public const OUT_OF_STOCK = 2;
    public const ON_DEMAND = 3;
    public const COMING_SOON = 4;

    protected static string $primaryKey = "product_mutation_id";
    protected static string $tableName = "products_mutations";

    public int $product_mutation_id;
    public int $product_id;

    public string $name;
    public string $description;
    public string $shortDescription;

    public string $alias;

    public ?float $buyPrice;
    public float $price;

    public VAT $vat;
    public Mutation $mutation;

    public int $quantityStock = 0;
    public int $stockStatus = self::IN_STOCK;

    public bool $published = false;

    public function getPriceVAT(): float {
        return $this->price * (100 + $this->vat->vat) / 100;
    }

    public function getPrice(): float {
        return $this->price;
    }

    public function getProductsByMutation(Mutation $mutation = null): array {
        if($mutation === null) {
            $mutation = ProjectTools::getMutation();
        }

        return $this->getAllByWhere([
            "mutation" => $mutation->getPrimaryKeyValue()
        ]);
    }

    public function getProductMutation(Product $product, Mutation $mutation = null): ?self {
        if($mutation === null) {
            $mutation = ProjectTools::getMutation();
        }

        $this->getByWhere([
            "mutation" => $mutation->getPrimaryKeyValue(),
            "product_id" => $product->getPrimaryKeyValue()
        ]);

        return $this;
    }

    public function isStockFree(int $quantity): bool {
        return $this->quantityStock >= $quantity;
    }

    public function __toString(): string {
        return $this->name;
    }
}