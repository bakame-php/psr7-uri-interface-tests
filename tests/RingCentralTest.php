<?php

namespace Bakame\Psr7\UriTest;

use RingCentral\Psr7\Uri;

/**
 * @group ringcentral
 */
final class RingCentralTest extends AbstractUriTestCase
{
    protected function createUri($uri = '')
    {
        return new Uri($uri);
    }
}