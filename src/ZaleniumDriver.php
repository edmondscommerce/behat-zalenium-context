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
     * Stored cookies to be added when the browser is available
     *
     * @var array
     */
    private $cookieBuffer = [];

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
        // Prevents the session being auto started before a test name has been set
        // Mink automatically starts the session when trying to retrieve it so we have to defend against
        // empty test names: \Behat\Mink\Mink::getSession
        if ($this->name === null) {
            return;
        }

        $this->desiredCapabilities['name'] = $this->name;
        $this->setDesiredCapabilities($this->desiredCapabilities);

        parent::start();

        $this->maximizeWindow();
    }

    public function stop()
    {
        $this->name = null;
        parent::stop();
    }

    /**
     * Used to send Zalenium cookies without the URL encoding
     * If this is not used then the values will have spaces replaced with + etc
     * This was added as I did not want to override the vanilla cookie logic
     * This is purely for Zalenium's requirements.
     *
     * @param string $name
     * @param null   $value
     */
    public function sendZaleniumCookie(string $name, string $value = null): void
    {
        if (null === $value) {
            $this->getWebDriverSession()->deleteCookie($name);

            return;
        }

        $cookieArray = [
            'name'   => $name,
            'value'  => $value,
            'secure' => false,
        ];

        $this->getWebDriverSession()->setCookie($cookieArray);
    }

    public function setName(string $name)
    {
        $this->name = $name;

        return $this;
    }
}
