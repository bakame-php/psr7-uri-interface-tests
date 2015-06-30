<?php

namespace Psr7\UriInterface\Testsuite;

use League\Uri\Url;

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
