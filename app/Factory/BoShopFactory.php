<?php


namespace BoShop\Factory;


use BoShop\System\Project;

class BoShopFactory extends AbstractFactory
{

    public static function produce()
    {
        \BoShop\factory\EnvironmentFactory::produce();
        \BoShop\factory\ErrorHandlerFactory::produce();

        //Anti-piracy check and activated
        $project = new Project();
        $project->getByDomain($_SERVER["HTTP_HOST"]);

        if(($project->project_id ?? null) && $project->isActivated()) {
            \BoShop\Factory\RouterFactory::produce();
        } else {
            throw new \Exception("Domain was not found in any project or mutation");
        }
    }
}