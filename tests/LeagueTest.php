<?php

namespace Bakame\Psr7\UriTest;

use League\Uri\Schemes\Http;

/**
 * @group league
 */
final class LeagueTest extends AbstractUriTestCase
{
    protected function createUri($uri = '')
    {
        return Http::createFromString($uri);
    }
}