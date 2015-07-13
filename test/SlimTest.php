<?php

namespace Psr7\UriInterface\Testsuite;

use Slim\Http\Uri;
use PHPUnit_Framework_TestCase as TestCase;

/**
 * @group slim
 */
class SlimTest extends TestCase
{
    use UriInterfaceTestsTrait;

    /**
     * {@inheritdoc}
     */
    public function createUriObject($url)
    {
        return Uri::createFromString($url);
    }
}
