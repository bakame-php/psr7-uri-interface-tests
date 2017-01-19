<?php

namespace Bakame\Psr7\UriTest;

use Jasny\HttpMessage\Uri;

/**
 * @group jasny
 */
final class JasnyTest extends AbstractUriTestCase
{
    protected function createUri($uri = '')
    {
        return new Uri($uri);
    }
}