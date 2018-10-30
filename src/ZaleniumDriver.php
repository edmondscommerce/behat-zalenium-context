<?php

namespace EdmondsCommerce\ZaleniumContext;

use Behat\Mink\Driver\Selenium2Driver;

class ZaleniumDriver extends Selenium2Driver
{
    private $name;

    public function start()
    {
        if ($this->name === null)
        {
            return;
        }

        $this->setDesiredCapabilities(['name' => $this->name]);
        parent::start();
    }

    public function stop()
    {
        $this->name = null;
        parent::stop();
    }

    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }
}