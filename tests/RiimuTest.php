<?php

namespace Bakame\Psr7\UriTest;

use Riimu\Kit\UrlParser\Uri;

/**
 * @group riimu
 */
final class RiimuKitTest extends AbstractUriTestCase
{
    protected function createUri($uri = '')
    {
        return new Uri($uri);
    }
}