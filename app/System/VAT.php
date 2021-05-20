<?php


namespace BoShop\System;


use BoShop\Database\DatabaseRow;

class VAT extends DatabaseRow
{

    protected static string $primaryKey = "vat_id";
    protected static string $tableName = "vats";

    public int $vat_id;

    public float $vat;

    public Mutation $mutation;

    public string $name;

    public function getByMutation(Mutation $mutation): array {
        return $this->getAllByWhere([
            "mutation" => $mutation->getPrimaryKeyValue()
        ]);
    }
}