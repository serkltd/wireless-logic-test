# Technical Test

> For the Software Engineering Team at Wireless Logic

## Table of Contents

* [General Information](#general-information)
* [Technologies Used](#technologies-used)
* [Symfony Components](#symfony-components)
* [Setup](#setup)
* [Usage](#usage)
* [Tests](#tests)
* [Assumptions](#assumptions)
* [Potential Improvements](#potential-improvements)
* [Contact](#contact)

<!-- * [License](#license) -->


## General Information

This is a PHP based console application that scrapes the following website url https://wltest.dns-systems.net/ and returns a JSON array of all product options on the page. 

The output is sorted by annual price, in ascending order.


## Technologies Used

- [Docker](https://www.docker.com/)
- [PHP 8.2](https://www.php.net/)
- [Symfony 6.2](https://symfony.com/)
- [Composer](https://getcomposer.org/)
- [NGINX](https://www.nginx.com/)


## Symfony Components

- [symfony/css-selector](https://github.com/symfony/css-selector)
- [symfony/dom-crawler](https://github.com/symfony/dom-crawler)
- [symfony/http-client](https://github.com/symfony/http-client)
- [symfony/test-pack](https://github.com/symfony/test-pack)


## Setup

To run this app, you will need to have [Docker Desktop](https://www.docker.com/products/docker-desktop/) installed.

Once installed, build and run the project containers for the first time by executing the following command.

```sh
make build
```


## Usage

Then run the app by executing the following command.

```sh
make run
```

This should provide the following output:
```
serkanahmet@Serkans-MBP wireless-logic-test % make run
[
    {
        "option title": "Optimum: 2 GB Data - 12 Months",
        "description": "The optimum subscription providing you with enough service time to support the above-average user to enable your device to be up and running with inclusive Data and SMS services",
        "price": "£15.99",
        "monthly price": "£15.99",
        "annual price": "£191.88",
        "discount": "£0.00",
        "type": "MONTHLY_OPTION"
    },
    {
        "option title": "Optimum: 24GB Data - 1 Year",
        "description": "The optimum subscription providing you with enough service time to support the above-average with data and SMS services to allow access to your device.",
        "price": "£174.00",
        "monthly price": "£14.50",
        "annual price": "£174.00",
        "discount": "£17.90",
        "type": "ANNUAL_OPTION"
    },
    {
        "option title": "Standard: 1GB Data - 12 Months",
        "description": "The standard subscription providing you with enough service time to support the average user to enable your device to be up and running with inclusive Data and SMS services.",
        "price": "£9.99",
        "monthly price": "£9.99",
        "annual price": "£119.88",
        "discount": "£0.00",
        "type": "MONTHLY_OPTION"
    },
    {
        "option title": "Standard: 12GB Data - 1 Year",
        "description": "The standard subscription providing you with enough service time to support the average user with Data and SMS services to allow access to your device.",
        "price": "£108.00",
        "monthly price": "£9.00",
        "annual price": "£108.00",
        "discount": "£11.90",
        "type": "ANNUAL_OPTION"
    },
    {
        "option title": "Basic: 500MB Data - 12 Months",
        "description": "The basic starter subscription providing you with all you need to get your device up and running with inclusive Data and SMS services.",
        "price": "£5.99",
        "monthly price": "£5.99",
        "annual price": "£71.88",
        "discount": "£0.00",
        "type": "MONTHLY_OPTION"
    },
    {
        "option title": "Basic: 6GB Data - 1 Year",
        "description": "The basic starter subscription providing you with all you need to get you up and running with Data and SMS services to allow access to your device.",
        "price": "£66.00",
        "monthly price": "£5.50",
        "annual price": "£66.00",
        "discount": "£5.86",
        "type": "ANNUAL_OPTION"
    }
]
```


## Tests

To run the tests, execute the following command.

```sh
make test
```

This should provide the following output:

```
serkanahmet@Serkans-MBP wireless-logic-test % make test
PHPUnit 9.6.3 by Sebastian Bergmann and contributors.

Testing 
............                                                      12 / 12 (100%)

Time: 00:01.570, Memory: 12.00 MB

OK (12 tests, 63 assertions)
```


## Assumptions

- The output should at minimum contain with keys named exactly as described in the brief.
- It is acceptable to add extra fields to the output originally described in the brief, if potentially useful.
- To avoid confusion, the `price` field in the output represents the price displayed on the webpage. Additional fields have been provided in the output to provide additional context, and show how much the option costs both monthly and annually, with calculations performed where needed.
- No need to persist data, therefore no need for a database.
- We want to display currency symbols in the output.


## Potential Improvements

- Consider a Command/Command Handler design pattern to encapsulate and handle the single use case described in the brief. Using a command bus like Symfony Messenger can help achieve this.
- Consider returning Collection classes where appropriate, to aid the handling of arrays.
- Consider a more fleshed out Money object for `ProductOption` price attributes.
- Consider a Repository layer.
- Better exception handling, custom exceptions, etc.
- More tests.


## Contact
Created by [Serkan Ahmet](mailto:serkltd@gmail.com) on a Mac - feel free to say hello!
