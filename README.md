[WIP] PSR-7 UriInterface test suite
=======

Motivation
-------

While developing [League Url](https://github.com/thephpleague/url/) version 4, I wanted to:

- implement the [PSR-7 UriInterface](http://php-fig.org/psr-7/#3-5-psr-http-message-uriinterface);
- compare objects implementing this interface easily with a `Url::sameValueAs` method;

On the `League\Url` test suite the method worked because I mocked the interface but on real world implementation it failed miserably. So I setup this test suite to compare implementations against what the interface expects.

This is a work in progress. Feel free to update and improve the tests. It will help everyone get a real interoperable `UriInterface`.

Tested implementations
-------

Out of the box this package can run the tests against the following implementations (order alphabetically):

- [Guzzle PSR-7](https://github.com/guzzle/psr7)
- [League Url](https://github.com/thephpleague/url/) (version 4.x)
- [Slim](https://github.com/slimphp/Slim/tree/3.x) (version 3.x)
- [Zend Diactoros](https://github.com/zendframework/zend-diactoros)

System Requirements
-------

You need:

- **PHP >= 5.5.0** or **HHVM >= 3.6**, but the latest stable version of PHP/HHVM is recommended
- the `mbstring` extension
- the `intl` extension

Install
-------

Clone this repo on a composer installed box and run the following command from the project folder.

``` bash
$ composer install
```

Testing
-------

To run the tests, run the following command from the project folder.

``` bash
$ phpunit
```

Adding a new implementation
-------

- Make sure your PSR-7 `UriInterface` interface implementation is available on [packagist](https://packagist.org) first
- Clone this repo
- Update the `composer.json` file with your package
- Add a new class in the `test` directory for your implementation that extends the `AbstractTestPsr7UriInterface` abstract class. You can copy/paste an implementation test suite to see how it works. Implements the `createUriObject` abstract method.
- Update the `UriInterfaceTest::urlProvider` with your implementation
- run the test suite.
- you can submit your implementation via Pull Request (don't forget to update the `README.md` file with a link to your repo in the Tested implementation section).


Contributing
-------

Contributions are welcome and will be fully credited. Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

Credits
-------

- [ignace nyamagana butera](https://github.com/nyamsprod)
- [All Contributors](https://github.com/nyamsprod/psr7-uri-interface-test-suite/contributors)

License
-------

The MIT License (MIT). Please see [License File](LICENSE) for more information.
