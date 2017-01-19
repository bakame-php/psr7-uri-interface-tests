<?php

namespace Bakame\Psr7\UriTest;

use Windwalker\Uri\PsrUri;

/**
 * @group windwalker
 */
final class WindwalkerTest extends AbstractUriTestCase
{
    protected function createUri($uri = '')
    {
        return new PsrUri($uri);
    }
}