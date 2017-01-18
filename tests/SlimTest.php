<?php

namespace Bakame\Psr7\UriTest;

use Slim\Http\Uri;

/**
 * @group slim
 */
final class SlimTest extends AbstractUriTestCase
{
    protected function createUri($uri = '')
    {
        return Uri::createFromString($uri);
    }
}