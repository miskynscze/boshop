<?php


namespace BoShop\System\Products;


use BoShop\Database\DatabaseRow;
use BoShop\System\Cart\CartVirtualItem;
use BoShop\System\Mutation;
use BoShop\System\VAT;
use BoShop\Tools\ProjectTools;

class ProductMutation extends DatabaseRow
{

    public const IN_STOCK = 1;
    public const OUT_OF_STOCK = 2;
    public const ON_DEMAND = 3;
    public const COMING_SOON = 4;
    public const RESERVED = 5;

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

    public function getStockQuantity(): ?int {
        if($this->quantityStock <= 0) {
            return null;
        }

        $productVariant = new ProductVariant();
        $productVariant->getByWhere([
            "product_mutation" => $this->getPrimaryKeyValue()
        ]);

        $cartVirtualItem = new CartVirtualItem();
        $cartVirtualItem->getByProduct($this, $productVariant);

        if($cartVirtualItem->cart_virtual_item_id ?? null) {
            $quantityAfterVirtual = $this->quantityStock - $cartVirtualItem->quantity;
        } else {
            $quantityAfterVirtual = $this->quantityStock;
        }

        if($quantityAfterVirtual === 0) {
            return null;
        } elseif($quantityAfterVirtual < 0) {
            throw new \Exception("Fatal error: Virtual cart item caused negative stock!");
        }

        return $quantityAfterVirtual;
    }

    public function getQuantityNoCalculation(): int {
        return $this->quantityStock;
    }

    public function reloadProduct(): void {
        $this->getById($this->getPrimaryKeyValue());
    }

    public function setQuantity(int $quantity): void {
        $this->quantityStock = $quantity;
    }

    public function __toString(): string {
        return $this->name;
    }
}