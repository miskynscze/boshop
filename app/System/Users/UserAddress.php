<?php


namespace BoShop\System\Users;


use BoShop\Database\DatabaseRow;

class UserAddress extends DatabaseRow
{

    protected static string $primaryKey = "user_address_id";
    protected static string $tableName = "users_addresses";

    public int $user_address_id;

    public int $type;

    public string $street;
    public string $zip;
    public string $city;
    public string $house_number;

    public string $phone_number;
    public string $email_address;

    public string $dateCreated;
    public string $dateUpdated;
}