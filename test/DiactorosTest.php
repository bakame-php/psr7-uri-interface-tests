<?php

namespace Psr7\UriInterface\Testsuite;

use Zend\Diactoros\Uri;

class DiactorosTest extends AbstractTestPsr7UriInterface
{
    /**
     * {@inheritdoc}
     */
    public function createUriObject($url)
    {
        return new Uri($url);
    }
}
