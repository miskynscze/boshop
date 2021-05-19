<?php


namespace BoShop\Database;


use Medoo\Medoo;

class Database
{

    private Medoo $database;

    public function connect($host, $user, $pass, $db, $type = "mysql") {
        $this->database = new Medoo([
            "type" => $type,
            "database" => $db,
            "host" => $host,
            "username" => $user,
            "password" => $pass,

            "charset" => "utf8mb4",
            "collation" => "utf8mb4_general_ci"
        ]);
    }

    public function getDb(): Medoo {
        return $this->database;
    }
}