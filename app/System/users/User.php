<?php


namespace BoShop\System\Users;


use BoShop\Database\DatabaseRow;

class User extends DatabaseRow
{

    protected static string $primaryKey = "user_id";
    protected static string $tableName = "users";

    public int $user_id;

    public string $firstname;
    public string $lastname;

    public string $email;
    public string $password;

    public bool $activated = false;
    public bool $blocked = false;
    public bool $verified = false;

    public string $dateCreated;
    public string $dateUpdated;
}