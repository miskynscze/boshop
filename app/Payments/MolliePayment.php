<?php


namespace BoShop\Payments;


use Mollie\Api\MollieApiClient;

class MolliePayment
{

    public function createClient(): MollieApiClient {
        $client = new MollieApiClient();
        $client->setApiKey("test_GkQMHemKunzbF29jzv7cjb7v6ht232");

        return $client;
    }

    public function createPayment(): void {
        $client = $this->createClient();

        $payment = $client->payments->create([
            "method" => "creditcard",
            "amount" => [
                "currency" => "EUR",
                "value" => "100"
            ],
            "description" => "Order #202101001",
            "redirectUrl" => "http://boshop.localhost/paymentMollie",
            "webhookUrl" => "http://boshop.localhost/webhook"
        ]);
    }
}