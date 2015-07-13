<?php

namespace Psr7\UriInterface\Testsuite;

use GuzzleHttp\Psr7\Uri;
use PHPUnit_Framework_TestCase as TestCase;

/**
 * @group guzzle
 */
class GuzzleTest extends TestCase
{
    use UriInterfaceTestsTrait;

    /**
     * {@inheritdoc}
     */
    public function createUriObject($url)
    {
        return new Uri($url);
    }
}
