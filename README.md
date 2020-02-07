# Behat Zalenium Context
## By [Edmonds Commerce](https://www.edmondscommerce.co.uk)

A Behat Context for integrating with Zalenium and allowing split videos for test scenarios

The context passes message to Zalenium through the use of Browser cookies:
* To indicate test pass and failure
* Add current step message to output video
* Some error handling

## Requirements
* PHP7.0+
* Using Zalenium (based on Selenium 3)
* Docker (for Zalenium to run)
* Using Chrome in automated tests

## Installation

Install via composer - 
```bash
composer require edmondscommerce/behat-zalenium-context:dev-master@dev
```

*if you are using this in your main repository we suggest add this as a dev dependency with the `--dev` flag*

## Configuration 

### Use ZaleniumExtension
Replace the `MinkExtension` in your `behat.yml` file with the `ZaleniumExtension`.
This is required to allow the Zalenium messages to be set in the videos correctly.

```yaml
default:
  extensions:
    Behat\MinkExtension:     
```

Becomes

```yaml
default:
  extensions:
    EdmondsCommerce\ZaleniumContext\ZaleniumExtension:
```

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

### Update your profile to use Zalenium
This will allow the `ZaleniumDriver` to work in place of `Selenium2Driver`.
The `ZaleniumDriver` is an extension of the `Selenium2Driver`.

```yaml
# ...
        selenium_chrome_session_headless:
          selenium2:
            browser: chrome
            capabilities:
# ...
```

Becomes

```yaml
# ...
        selenium_chrome_session:
          zalenium:
            browser: chrome
            capabilities:
# ...
```

## Usage

See our handbook page in the links below for further information on how to use Zalenium with Behat.
If you find a bug or want to help improve the extension, drop us an issue/pull request!

### Notes
By default, the `ZaleniumDriver` will disable W3C mode, this is not supported by the underlying web driver.
As a matter of convenience, this has been done in the driver itself, along with the allowance of insecure SSL.
These options are automatically added to the desired capabilities.
 
## Links
[Packagist](https://packagist.org/packages/edmondscommerce/zalenium-context)

[Zalenium Github](https://github.com/zalando/zalenium)

[Zalenium on the EC handbook](https://www.edmondscommerce.co.uk/handbook/Development-Tools/Testing/Zalenium/)