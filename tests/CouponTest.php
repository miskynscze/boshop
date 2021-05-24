<?php


use BoShop\System\Orders\Coupon;

class CouponTest extends \PHPUnit\Framework\TestCase
{

    public function testValueCoupon() {
        $value = 100;

        self::assertEquals(50, $this->getTestCouponValue()->calculate($value));
    }

    public function testValuePercent() {
        $value = 100;

        self::assertEquals(90, $this->getTestCouponPercent()->calculate($value));
    }

    private function getTestCouponValue(): Coupon {
        $coupon = new Coupon();
        $coupon->name = "Kupon 10";
        $coupon->type = Coupon::VALUE;
        $coupon->value = 50;

        return $coupon;
    }

    private function getTestCouponPercent(): Coupon {
        $coupon = new Coupon();
        $coupon->name = "Kupon 10%";
        $coupon->value = 10;

        return $coupon;
    }
}