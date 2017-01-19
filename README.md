Unit tests for PSR-7 UriInterface
=======

Tested implementations
-------

Out of the box this package can run the tests against the following implementations (order alphabetically):

- [Guzzle PSR-7](https://github.com/guzzle/psr7)
- [Jasny HTTP Message](https://github.com/jasny/http-message)
- [League URI schemes](https://github.com/thephpleague/uri-schemes/)
- [PHPixie](https://github.com/PHPixie/HTTP)
- [Riimu KIT UrlParser](https://github.com/Riimu/Kit-UrlParser)
- [Slim](https://github.com/slimphp/Slim)
- [Spatie URL](https://github.com/spatie/url)
- [Windwalker URI](https://github.com/ventoviro/windwalker-uri)
- [Zend Diactoros](https://github.com/zendframework/zend-diactoros)

System Requirements
-------

You need:

- The latest stable version of PHP is recommended
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

You can also run different tests according to the following groups:

- `@group scheme` runs only scheme related tests;
- `@group userinfo` runs only userinfo related tests;
- `@group host` runs only host related tests;
- `@group port` runs only port related tests;
- `@group authority` runs only authority related tests;
- `@group path` runs only path related tests;
- `@group query` runs only query related tests;
- `@group fragment` runs only fragment related tests;
- `@group uri` runs only uri related tests;
- `@group diactoros` runs all the tests for `Zend\Diactoros\Uri`
- `@group guzzle` runs all the tests for `Guzzle\Psr7\Uri`
- `@group jasny` runs all the tests for `Jasny\HttpMessage\Uri`
- `@group league` runs all the tests for `League\Uri\Schemes\Http`
- `@group phpixie` runs all the tests for `PHPixie\HTTP\Messages\URI\Implementation`
- `@group riimu` runs all the tests for `Riimu\Kit\UrlParser\Uri`
- `@group slim` runs all the tests for `Slim\HTTP\Uri`
- `@group spatie` runs all the tests for `Spatie\Url\Url`
- `@group windwalker` runs all the tests for `Windwalker\Uri\PsrUri`

example

``` bash
$ phpunit --group port
```

Adding a new implementation
-------

- Make sure your PSR-7 `UriInterface` interface implementation is available on [packagist](https://packagist.org) first
- Clone this repo
- Update the `composer.json` file with your package
- Add a new class in the `tests` directory for your implementation that extends `Bakame\Psr7\UriTest\AbstractUriTestCase` by providing a URI factory to bootstrap URI object creation from your library.

Here's a example to copy/paste and edit

```php
<?php

namespace Bakame\Psr7\UriTest;

use My\Library\Uri;

/**
 * @group my-library
 */
final class MyLibraryTest extends AbstractUriTestCase
{
    protected function createUri($uri = '')
    {
        return new Uri($uri);
    }
}
```

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
