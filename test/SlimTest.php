<?php

namespace Psr7\UriInterface\Testsuite;

use Slim\Http\Uri;

class SlimTest extends AbstractTestPsr7UriInterface
{
    /**
     * {@inheritdoc}
     */
    public function createUriObject($url)
    {
        return Uri::createFromString($url);
    }
}
