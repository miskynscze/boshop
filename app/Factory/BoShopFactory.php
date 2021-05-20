<?php


namespace BoShop\Factory;


use BoShop\System\Mutation;

class BoShopFactory extends AbstractFactory
{

    public static function produce()
    {
        \BoShop\factory\EnvironmentFactory::produce();
        \BoShop\factory\ErrorHandlerFactory::produce();

        //Anti-piracy check
        $mutation = new Mutation();
        $mutation->getByDomain($_SERVER["HTTP_HOST"]);

        if($mutation->mutation_id ?? null) {
            \BoShop\Factory\RouterFactory::produce();
        } else {
            throw new \Exception("Domain was not found in any project or mutation");
        }
    }
}