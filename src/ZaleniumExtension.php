<?php

namespace EdmondsCommerce\ZaleniumContext;

use Behat\MinkExtension\ServiceContainer\MinkExtension;
use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;

class ZaleniumExtension extends MinkExtension
{
    public function __construct()
    {
        parent::__construct();

        $this->registerDriverFactory(new ZaleniumDriverFactory());
    }

    private function loadMink(ContainerBuilder $container)
    {
        $container->setDefinition(self::MINK_ID, new Definition('Behat\Mink\Mink'));
    }
}