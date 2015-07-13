<?php

namespace Psr7\UriInterface\Testsuite;

use League\Uri\Schemes\Http;
use PHPUnit_Framework_TestCase as TestCase;

/**
 * @group league
 */
class LeagueUrlTest extends TestCase
{
    use UriInterfaceTestsTrait;

    /**
     * {@inheritdoc}
     */
    public function createUriObject($url)
    {
        return Http::createFromString($url);
    }
}
