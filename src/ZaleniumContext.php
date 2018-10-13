<?php

namespace EdmondsCommerce\ZaleniumContext;

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
     * @BeforeScenario
     */
    public function maximize(\Behat\Behat\Hook\Scope\BeforeScenarioScope $scope)
    {
        $this->getSession()->maximizeWindow();
    }

    /**
     * @AfterScenario
     * @param \Behat\Behat\Hook\Scope\AfterScenarioScope $scope
     */
    public function reportTestStatus(\Behat\Behat\Hook\Scope\AfterScenarioScope $scope)
    {
        if ($scope->getTestResult()->isPassed())
        {
            $this->reportSuccess();
        } else
        {
            $this->reportFailure();
        }

        $this->getSession()->restart();
    }

    /**
     * Tries to set a cookie with out causing errors
     * @param string $name
     * @param string $value
     * @throws \Exception
     */
    private function setCookie(string $name, string $value)
    {
        try
        {
            // Don't continue if an alert box is open
            if (!$this->isAlertOpen())
            {
                $this->getSession()->setCookie($name, $value);
            }
        } catch (\Exception $e)
        {
            //This only happens when we can't visit a page
            if (strpos($e->getMessage(), 'unable to set cookie') === 0)
            {
                // Cookie exception, prevent it from stopping the test
                echo $e->getMessage();
                return;
            }

            throw $e;
        }
    }

    private function isAlertOpen(): bool
    {
        $driver = $this->getSession()->getDriver();
        if ($driver instanceof \Behat\Mink\Driver\Selenium2Driver)
        {
            try
            {
                $driver->getWebDriverSession()->getAlert_text();
            } catch (\WebDriver\Exception\NoAlertOpenError $e)
            {
                return false;
            }
            return true;
        }
    }

    /**
     * @AfterStep
     * @param \Behat\Behat\Hook\Scope\AfterStepScope $scope
     */
    public function reportTestStep(\Behat\Behat\Hook\Scope\AfterStepScope $scope)
    {
        $scenarioName = $this->currentScenario->getTitle();
        $text         = $scope->getStep()->getText();

        $this->reportStep($scenarioName, $text);
    }

    private function reportStep(string $testName, string $content)
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
