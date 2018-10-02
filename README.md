# Behat Zalenium Context
## By [Edmonds Commerce](https://www.edmondscommerce.co.uk)

A Behat Context for integrating with Zalenium and allowing split videos for test scenarios

The context passes message to Zalenium through the use of Browser cookies:
* To indicate test pass and failure
* Add current step message to output video
* Some error handling

### Requirements
* PHP7.0+
* Using Zalenium (based on Selenium 3)
* Docker (for Zalenium to run)
* Using Chrome in automated tests

### Installation

Install via composer

"edmondscommerce/behat-zalenium-context": "dev-master@dev"


### Include Context in Behat Configuration

```yaml
default:
    # ...
    suites:
        default:
        # ...
            contexts:
                - # ...
                - EdmondsCommerce\ZaleniumContext\ZaleniumContext
```

## Links
[Packagist](https://packagist.org/packages/edmondscommerce/zalenium-context)

[Zalenium Github](https://github.com/zalando/zalenium)

[Zalenium on the EC handbook](https://www.edmondscommerce.co.uk/handbook/Development-Tools/Testing/Zalenium/)