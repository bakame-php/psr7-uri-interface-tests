<?php

namespace Bakame\Psr7\UriTest;

use PHPixie\HTTP\Messages\URI\Implementation as Uri;

/**
 * @group phpixie
 */
final class PHPixieTest extends AbstractUriTestCase
{
    protected function createUri($uri = '')
    {
        return new Uri($uri);
    }
}