<?php

namespace Psr7\UriInterface\Testsuite;

use GuzzleHttp\Psr7\Uri;

class GuzzleTest extends AbstractTestPsr7UriInterface
{
    /**
     * {@inheritdoc}
     */
    public function createUriObject($url)
    {
        return new Uri($url);
    }
}
