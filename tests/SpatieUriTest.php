<?php

namespace Bakame\Psr7\UriTest;

use Spatie\Url\Url;

/**
 * @group spatie
 */
final class SpatieTest extends AbstractUriTestCase
{
    protected function createUri($uri = '')
    {
        return Url::fromString($uri);
    }
}