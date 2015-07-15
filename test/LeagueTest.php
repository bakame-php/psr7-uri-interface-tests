<?php

namespace Psr7\UriInterface\Testsuite;

use League\Uri\Schemes\Http;
use PHPUnit_Framework_TestCase as TestCase;

/**
 * @group league
 */
class LeagueTest extends TestCase
{
    use UriInterfaceTestsTrait;

    /**
     * {@inheritdoc}
     */
    public function createDefaultUri()
    {
        return $this->createUriObject();
    }

    public function createUriObject($url = '')
    {
        return Http::createFromString($url);
    }
}
