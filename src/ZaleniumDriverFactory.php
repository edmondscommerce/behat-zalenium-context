<?php

namespace EdmondsCommerce\ZaleniumContext;

use Behat\MinkExtension\ServiceContainer\Driver\Selenium2Factory;

class ZaleniumDriverFactory extends Selenium2Factory
{
    /**
     * {@inheritdoc}
     */
    public function getDriverName()
    {
        return 'zalenium';
    }

    public function buildDriver(array $config)
    {
        return parent::buildDriver($config)->setClass(ZaleniumDriver::class);
    }
}