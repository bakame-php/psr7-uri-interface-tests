<?php

namespace Bakame\Psr7\UriTest;

use Zend\Diactoros\Uri;

/**
 * @group diactoros
 */
final class DiactorosTest extends AbstractUriTestCase
{
    protected function createUri($uri = '')
    {
        return new Uri($uri);
    }
}