<?php

namespace Psr7\UriInterface\Testsuite;

use Slim\Http\Uri;

/**
 * @group slim
 */
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
