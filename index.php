<?php
require "vendor/autoload.php";

\BoShop\factory\EnvironmentFactory::produce();
\BoShop\factory\ErrorHandlerFactory::produce();
\BoShop\Factory\RouterFactory::produce();