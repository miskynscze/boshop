<?php


namespace BoShop\System\Orders;


use BoShop\Database\DatabaseRow;
use BoShop\System\Mutation;
use BoShop\System\Users\User;
use BoShop\System\Users\UserAddress;

class Order extends DatabaseRow
{

    public const WAIT = 0;
    public const PAID = 1;
    public const PREPARING = 2;
    public const SEND = 3;
    public const DELIVERED = 4;
    public const CANCELED = 5;
    public const REFUND = 6;

    protected static string $primaryKey = "order_id";
    protected static string $tableName = "orders";
    protected static array $ignoreVars = ["orderItems"];

    public int $order_id;

    public User $user_id;
    public ?UserAddress $deliveryAddress;
    public ?UserAddress $invoiceAddress;

    public Mutation $mutation;

    public ?Coupon $coupon;

    public int $status = self::WAIT;

    public string $dateCreated;
    public string $dateUpdated;

    public array $orderItems;

    private float $priceVAT;
    private float $price;
    private float $vat;

    public function save($forceInsert = false): bool
    {
        $this->setDateUpdated();
        return parent::save($forceInsert);
    }

    public function getById(int $id): ?self
    {
        /** @var Order $order */
        $order = parent::getById($id);

        if($order !== null) {
            $order->loadItems();
        }

        return $order;
    }

    public function loadItems(): void {
        $orderItems = new OrderItem();
        $this->orderItems = $orderItems->getByOrder($this);
    }

    public function setStatus($status): void {
        $this->status = $status;
        $this->save();
    }

    public function isStatus($status): bool {
        return $this->status === $status;
    }

    public function getDeliveryAddress(): UserAddress {
        return $this->deliveryAddress ?? $this->user_id->deliveryAddress;
    }

    public function getInvoiceAddress(): UserAddress {
        return $this->invoiceAddress ?? $this->user_id->invoiceAddress;
    }

    public function getUser(): ?User {
        return $this->user_id;
    }

    public function getMutation(): Mutation {
        return $this->mutation;
    }

    public function getPriceVAT(): float {
        if($this->priceVAT ?? null)
            return $this->priceVAT;

        $price = 0;

        /** @var OrderItem $orderItem */
        foreach ($this->orderItems as $orderItem) {
            $price += $orderItem->getPriceVAT();
        }

        if($this->coupon ?? null) {
            $price = $this->coupon->calculate($price);
        }

        $this->priceVAT = $price;

        return $price;
    }

    public function getPrice(): float {
        if($this->price ?? null)
            return $this->price;

        $price = 0;

        /** @var OrderItem $orderItem */
        foreach ($this->orderItems as $orderItem) {
            $price += $orderItem->getPrice();
        }

        if($this->coupon ?? null) {
            $price = $this->coupon->calculate($price);
        }

        $this->price = $price;

        return $price;
    }

    public function getVAT(): float {
        if($this->vat ?? null)
            return $this->vat;

        $priceVAT = $this->getPriceVAT();
        $price = $this->getPrice();

        $vat = $priceVAT - $price;
        $this->vat = $vat;

        return $vat;
    }

    public function getOrderItems(): array {
        return $this->orderItems;
    }

    public function setCoupon(?Coupon $coupon): void {
        $this->coupon = $coupon;
    }

    private function setDateUpdated(): void {
        $dateUpdated = new \DateTime();
        $this->dateUpdated = $dateUpdated->format("Y-m-d H:i:s");
    }
}