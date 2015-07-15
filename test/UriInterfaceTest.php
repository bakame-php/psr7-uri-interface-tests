<?php

namespace Psr7\UriInterface\Testsuite;

use PHPUnit_Framework_TestCase as TestCase;

/**
 * @group compare
 */
class UriInterfaceTest extends TestCase
{
    /**
     * @dataProvider urlProvider
     * @group toString
     */
    public function testToString($url, $testObj1, $testObj2)
    {
        $this->assertSame(
            (new $testObj1())->createUriObject($url)->__toString(),
            (new $testObj2())->createUriObject($url)->__toString()
        );
    }

    /**
     * @dataProvider urlProvider
     * @group scheme
     */
    public function testGetScheme($url, $testObj1, $testObj2)
    {
        $this->assertSame(
            (new $testObj1())->createUriObject($url)->getScheme(),
            (new $testObj2())->createUriObject($url)->getScheme()
        );
    }

    /**
     * @dataProvider urlProvider
     * @group userinfo
     */
    public function testGetUserInfo($url, $testObj1, $testObj2)
    {
        $this->assertSame(
            (new $testObj1())->createUriObject($url)->getUserInfo(),
            (new $testObj2())->createUriObject($url)->getUserInfo()
        );
    }

    /**
     * @dataProvider urlProvider
     * @group host
     */
    public function testGetHost($url, $testObj1, $testObj2)
    {
        $this->assertSame(
            (new $testObj1())->createUriObject($url)->getHost(),
            (new $testObj2())->createUriObject($url)->getHost()
        );
    }

    /**
     * @dataProvider urlProvider
     * @group port
     */
    public function testGetPort($url, $testObj1, $testObj2)
    {
        $this->assertSame(
            (new $testObj1())->createUriObject($url)->getPort(),
            (new $testObj2())->createUriObject($url)->getPort()
        );
    }

    /**
     * @dataProvider urlProvider
     * @group authority
     */
    public function testGetAuthority($url, $testObj1, $testObj2)
    {
        $this->assertSame(
            (new $testObj1())->createUriObject($url)->getAuthority(),
            (new $testObj2())->createUriObject($url)->getAuthority()
        );
    }

    /**
     * @dataProvider urlProvider
     * @group path
     */
    public function testGetPath($url, $testObj1, $testObj2)
    {
        $this->assertSame(
            (new $testObj1())->createUriObject($url)->getPath(),
            (new $testObj2())->createUriObject($url)->getPath()
        );
    }

    /**
     * @dataProvider urlProvider
     * @group query
     */
    public function testGetQuery($url, $testObj1, $testObj2)
    {
        $this->assertSame(
            (new $testObj1())->createUriObject($url)->getQuery(),
            (new $testObj2())->createUriObject($url)->getQuery()
        );
    }

    /**
     * @dataProvider urlProvider
     * @group fragment
     */
    public function testGetFragment($url, $testObj1, $testObj2)
    {
        $this->assertSame(
            (new $testObj1())->createUriObject($url)->getFragment(),
            (new $testObj2())->createUriObject($url)->getFragment()
        );
    }

    public function urlProvider()
    {
        $urlList = [
            "url without scheme" => '//www.example.com/path/to/the/sky',
            "url without scheme, authority" => '/path/to/the/sky',
            "url with duplicate value query string" => "http://www.example.com?toto.foo=1&toto.foo=1",
            "url without path, query and fragment" => 'http://www.example.com',
            "url with case sensitive host" => 'http://WwW.ExAmPlE.CoM',
            "url with full components" => "HtTpS://igor:rasmusen@MaStEr.eXaMpLe.CoM:443/%7ejohndoe/%a1/index.php?foo.bar=value#fragment",
        ];

        $implementationTestSuite = [
            'diactoros' => 'Psr7\UriInterface\Testsuite\DiactorosTest',
            'guzzle'    => 'Psr7\UriInterface\Testsuite\GuzzleTest',
            'league'    => 'Psr7\UriInterface\Testsuite\LeagueTest',
            'slim'      => 'Psr7\UriInterface\Testsuite\SlimTest',
            'phpixie'   => 'Psr7\UriInterface\Testsuite\PhpixieTest',
        ];

        $res = [];
        foreach ($urlList as $name => $url) {
            foreach ($implementationTestSuite as $implementationA => $testsuiteA) {
                foreach ($implementationTestSuite as $implementationB => $testsuiteB) {
                    $res["$implementationA - $implementationB : $name"] = [$url, $testsuiteA, $testsuiteB];
                    if ($implementationA == $implementationB) {
                        break;
                    }
                }
            }
        }

        return $res;
    }
}
