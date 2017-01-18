<?php

namespace Bakame\Psr7\UriTest;

use GuzzleHttp\Psr7\Uri;

/**
 * @group guzzle
 */
final class GuzzleTest extends AbstractUriTestCase
{
    protected function createUri($uri = '')
    {
        return new Uri($uri);
    }
}