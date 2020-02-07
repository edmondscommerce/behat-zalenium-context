<?php

namespace EdmondsCommerce\ZaleniumContext;

use Behat\Mink\Driver\Selenium2Driver;

class ZaleniumDriver extends Selenium2Driver
{
    /**
     * @var array
     */
    private $desiredCapabilities;

    /**
     * @var string
     */
    private $name;

    public function __construct(
        $browserName = 'firefox',
        $desiredCapabilities = null,
        $wdHost = 'http://localhost:4444/wd/hub'
    ) {
        $this->desiredCapabilities = $desiredCapabilities;

        // Not compatible with W3C mode
        $this->desiredCapabilities['chromeOptions']['w3c'] = false;

        // Allow insecure (self signed) SSL
        $this->desiredCapabilities['acceptInsecureCerts'] = true;

        parent::__construct($browserName, $this->desiredCapabilities, $wdHost);
    }

    public function start()
    {
        if ($this->name === null) {
            return;
        }

        $this->desiredCapabilities['name'] = $this->name;
        $this->setDesiredCapabilities($this->desiredCapabilities);
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
