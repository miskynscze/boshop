<?php


namespace BoShop\System;


use BoShop\Database\DatabaseRow;

class Mutation extends DatabaseRow
{

    protected static string $primaryKey = "mutation_id";
    protected static string $tableName = "mutations";

    public int $mutation_id;
    public Project $project_id;

    public string $name;
    public string $domain;

    public bool $activated = false;

    public function getByDomain(string $domain) {
        $this->getByWhere([
            "domain" => $domain
        ]);
    }
}