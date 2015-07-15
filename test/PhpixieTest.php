<?php

namespace Psr7\UriInterface\Testsuite;

use PHPixie\HTTP\Messages\URI\Implementation;
use PHPUnit_Framework_TestCase as TestCase;

/**
 * @group phpixie
 */
class PhpixieTest extends TestCase
{
    use UriInterfaceTestsTrait;

    /**
     * {@inheritdoc}
     */
    public function createDefaultUri()
    {
        return $this->createUriObject();
    }

    public function createUriObject($url = '')
    {
        return new Implementation($url);
    }
}
