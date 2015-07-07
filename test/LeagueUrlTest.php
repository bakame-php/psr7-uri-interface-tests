<?php

namespace Psr7\UriInterface\Testsuite;

use League\Uri\Uri;

/**
 * @group league
 */
class LeagueUrlTest extends AbstractTestPsr7UriInterface
{
    /**
     * {@inheritdoc}
     */
    public function createUriObject($url)
    {
        return Uri::createFromString($url);
    }
}
