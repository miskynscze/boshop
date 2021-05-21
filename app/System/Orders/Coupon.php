<?php


namespace BoShop\System\Orders;


use BoShop\Database\DatabaseRow;

class Coupon extends DatabaseRow
{

    public const PERCENT = 1;
    public const VALUE = 2;

    protected static string $primaryKey = "coupon_id";
    protected static string $tableName = "coupons";

    public int $coupon_id;

    public string $name;
    public string $code;

    public float $value;

    public int $type = self::PERCENT;

    public bool $freeShipping = false;
    public bool $freePayment = false;

    public string $dateCreated;
    public string $dateUpdated;
    public ?string $dateFrom;
    public ?string $dateTo;

    public function calculate(float $price): float {
        //Percent coupon in %-
        if($this->type === self::PERCENT) {
            $price *= (100 - $this->value) / 100;
        }

        //Value -
        if($this->type === self::VALUE) {
            $price -= $this->value;
        }

        return $price;
    }

    public function getCouponCost(float $price): float {
        $calculated = $this->calculate($price);

        return $price - $calculated;
    }
}