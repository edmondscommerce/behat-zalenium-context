<?php

namespace EdmondsCommerce\ZaleniumContext;

use Behat\Behat\Hook\Scope\BeforeScenarioScope;
use Behat\Mink\Driver\Selenium2Driver;
use Behat\MinkExtension\Context\RawMinkContext;

class ZaleniumContext extends RawMinkContext
{
    /**
     * @var \Behat\Gherkin\Node\ScenarioInterface
     */
    private $currentScenario;

    /**
     * @BeforeScenario
     */
    public function setCurrentScenario(\Behat\Behat\Hook\Scope\BeforeScenarioScope $scope)
    {
        $this->currentScenario = $scope->getScenario();
    }

    /**
     * @param BeforeScenarioScope $scope
     *
     * @BeforeScenario
     */
    public function setTestName(BeforeScenarioScope $scope)
    {
        $driver = $this->getSession()->getDriver();
        if ($driver instanceof ZaleniumDriver) {
            $name = $scope->getScenario()->getTitle();
            if ($name === null) {
                throw new ZaleniumException('Test name not set, aborting. You must give scenarios a name!');
            }
            $driver->setName($name);
        }

    }

    /**
     * @AfterScenario
     *
     * @param \Behat\Behat\Hook\Scope\AfterScenarioScope $scope
     */
    public function reportTestStatus(\Behat\Behat\Hook\Scope\AfterScenarioScope $scope)
    {
        if ($scope->getTestResult()->isPassed()) {
            $this->reportSuccess();
        } else {
            $this->reportFailure();
        }

        $this->getSession()->restart();
    }

    /**
     * @AfterStep
     *
     * @param \Behat\Behat\Hook\Scope\AfterStepScope $scope
     */
    public function reportTestStep(\Behat\Behat\Hook\Scope\AfterStepScope $scope)
    {
        $scenarioName = $this->currentScenario->getTitle();
        $text         = $scope->getStep()->getText();

        $this->reportStep($scenarioName, $text);
    }

    /**
     * Tries to set a cookie with out causing errors
     *
     * @param string $name
     * @param string $value
     *
     * @throws \Exception
     */
    private function setCookie($name, $value)
    {
        try {
            // Don't continue if an alert box is open
            if (!$this->isAlertOpen()) {
                $this->getDriver()->sendZaleniumCookie($name, $value);
            }
        } catch (\Exception $e) {
            if ($this->isSkippableException($e)) {
                return;
            }

            throw $e;
        }
    }

    /**
     * We only support the Zalenium driver, if this call fails then something has gone wrong
     * and may indicate a configuration error.
     *
     * @return Selenium2Driver
     */
    private function getDriver(): ZaleniumDriver
    {
        return $this->getSession()->getDriver();
    }

    private function isSkippableException(\Exception $exception): bool
    {
        // Cookie errors only happen when we can't visit a page or we are not yet on the desired site in a step
        $skipErrors = [
            'unable to set cookie',
            'invalid cookie domain' // You can't set a cookie when you are not on a site
        ];

        foreach ($skipErrors as $skipError) {
            if (strpos($exception->getMessage(), $skipError) === 0) {
                echo $exception->getMessage();

                return true;
            }
        }

        return false;
    }

    private function isAlertOpen()
    {
        $driver = $this->getSession()->getDriver();
        if ($driver instanceof \Behat\Mink\Driver\Selenium2Driver) {
            try {
                $driver->getWebDriverSession()->getAlert_text();
            } catch (\WebDriver\Exception\NoAlertOpenError $e) {
                return false;
            }

            return true;
        }
    }

    /**
     * @param string $testName
     * @param string $content
     *
     * @throws \Exception#
     */
    private function reportStep($testName, $content)
    {
        $content = $testName . ': ' . $content;
        $this->setCookie('zaleniumMessage', $content);
    }

    private function reportSuccess()
    {
        $this->setCookie('zaleniumTestPassed', 'true');
    }

    private function reportFailure()
    {
        $this->setCookie('zaleniumTestPassed', 'false');
    }
}
