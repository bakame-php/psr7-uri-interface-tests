<?php

namespace Psr7\UriInterface\Testsuite;

use Zend\Diactoros\Uri;
use PHPUnit_Framework_TestCase as TestCase;

/**
 * @group diactoros
 */
class DiactorosTest extends TestCase
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
