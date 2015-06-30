<?php

namespace Psr7\UriInterface\Testsuite;

use League\Uri\Url;

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
        return Url::createFromString($url);
    }
}
