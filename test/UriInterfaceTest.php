<?php

namespace Psr7\UriInterface\Testsuite;

use PHPUnit_Framework_TestCase;
use ReflectionClass;

/**
 * @group compare
 */
class UriInterfaceTest extends PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider urlProvider
     */
    public function testToString($url, $classname1, $classname2)
    {
        $obj1 = (new ReflectionClass("Psr7\UriInterface\Testsuite\\".$classname1))->newInstance();
        $obj2 = (new ReflectionClass("Psr7\UriInterface\Testsuite\\".$classname2))->newInstance();
        $this->assertSame($obj1->createUriObject($url)->__toString(), $obj2->createUriObject($url)->__toString());
    }

    /**
     * @dataProvider urlProvider
     */
    public function testGetScheme($url, $classname1, $classname2)
    {
        $obj1 = (new ReflectionClass("Psr7\UriInterface\Testsuite\\".$classname1))->newInstance();
        $obj2 = (new ReflectionClass("Psr7\UriInterface\Testsuite\\".$classname2))->newInstance();
        $this->assertSame($obj1->createUriObject($url)->getScheme(), $obj2->createUriObject($url)->getScheme());
    }

    /**
     * @dataProvider urlProvider
     */
    public function testGetUserInfo($url, $classname1, $classname2)
    {
        $obj1 = (new ReflectionClass("Psr7\UriInterface\Testsuite\\".$classname1))->newInstance();
        $obj2 = (new ReflectionClass("Psr7\UriInterface\Testsuite\\".$classname2))->newInstance();
        $this->assertSame($obj1->createUriObject($url)->getUserInfo(), $obj2->createUriObject($url)->getUserInfo());
    }

    /**
     * @dataProvider urlProvider
     */
    public function testGetHost($url, $classname1, $classname2)
    {
        $obj1 = (new ReflectionClass("Psr7\UriInterface\Testsuite\\".$classname1))->newInstance();
        $obj2 = (new ReflectionClass("Psr7\UriInterface\Testsuite\\".$classname2))->newInstance();
        $this->assertSame($obj1->createUriObject($url)->getHost(), $obj2->createUriObject($url)->getHost());
    }

    /**
     * @dataProvider urlProvider
     */
    public function testGetPort($url, $classname1, $classname2)
    {
        $obj1 = (new ReflectionClass("Psr7\UriInterface\Testsuite\\".$classname1))->newInstance();
        $obj2 = (new ReflectionClass("Psr7\UriInterface\Testsuite\\".$classname2))->newInstance();
        $this->assertSame($obj1->createUriObject($url)->getPort(), $obj2->createUriObject($url)->getPort());
    }

    /**
     * @dataProvider urlProvider
     */
    public function testGetAuthority($url, $classname1, $classname2)
    {
        $obj1 = (new ReflectionClass("Psr7\UriInterface\Testsuite\\".$classname1))->newInstance();
        $obj2 = (new ReflectionClass("Psr7\UriInterface\Testsuite\\".$classname2))->newInstance();
        $this->assertSame($obj1->createUriObject($url)->getAuthority(), $obj2->createUriObject($url)->getAuthority());
    }

    /**
     * @dataProvider urlProvider
     */
    public function testGetPath($url, $classname1, $classname2)
    {
        $obj1 = (new ReflectionClass("Psr7\UriInterface\Testsuite\\".$classname1))->newInstance();
        $obj2 = (new ReflectionClass("Psr7\UriInterface\Testsuite\\".$classname2))->newInstance();
        $this->assertSame($obj1->createUriObject($url)->getPath(), $obj2->createUriObject($url)->getPath());
    }

    /**
     * @dataProvider urlProvider
     */
    public function testGetQuery($url, $classname1, $classname2)
    {
        $obj1 = (new ReflectionClass("Psr7\UriInterface\Testsuite\\".$classname1))->newInstance();
        $obj2 = (new ReflectionClass("Psr7\UriInterface\Testsuite\\".$classname2))->newInstance();
        $this->assertSame($obj1->createUriObject($url)->getQuery(), $obj2->createUriObject($url)->getQuery());
    }

    /**
     * @dataProvider urlProvider
     */
    public function testGetFragment($url, $classname1, $classname2)
    {
        $obj1 = (new ReflectionClass("Psr7\UriInterface\Testsuite\\".$classname1))->newInstance();
        $obj2 = (new ReflectionClass("Psr7\UriInterface\Testsuite\\".$classname2))->newInstance();
        $this->assertSame($obj1->createUriObject($url)->getFragment(), $obj2->createUriObject($url)->getFragment());
    }

    public function urlProvider()
    {
        $urlList = [
            "url without scheme" => '//www.example.com/path/to/the/sky',
            "url without scheme, authority" => '/path/to/the/sky',
            "url with duplicate value query string" => "http://www.example.com?toto.foo=1&toto.foo=2",
            "url without path, query and fragment" => 'http://www.example.com',
            "scheme + absolute path and no authority" => 'http:/example.com',
            "scheme + rootless path and no authority" => 'http:example.com',
            "url with case sensitive host" => 'http://WwW.ExAmPlE.CoM',
            "URL with full components" => "HtTpS://igor:rasmusen@MaStEr.eXaMpLe.CoM:443/%7ejohndoe/%a1/index.php?foo.bar=value#fragment",
        ];

        $res = [];
        foreach ($urlList as $name => $url) {
            $res["$name : league - league"]       = [$url, 'LeagueUrlTest', 'LeagueUrlTest'];
            $res["$name : league - guzzle"]       = [$url, 'LeagueUrlTest', 'GuzzleTest'];
            $res["$name : league - diactoros"]    = [$url, 'LeagueUrlTest', 'DiactorosTest'];
            $res["$name : league - slim"]         = [$url, 'LeagueUrlTest', 'SlimTest'];
            $res["$name : guzzle - guzzle"]       = [$url, 'GuzzleTest', 'GuzzleTest'];
            $res["$name : guzzle - diactoros"]    = [$url, 'GuzzleTest', 'DiactorosTest'];
            $res["$name : guzzle - slim"]         = [$url, 'GuzzleTest', 'SlimTest'];
            $res["$name : diactoros - diactoros"] = [$url, 'DiactorosTest', 'DiactorosTest'];
            $res["$name : diactoros - slim"]      = [$url, 'DiactorosTest', 'SlimTest'];
            $res["$name : slim - slim"]           = [$url, 'SlimTest', 'SlimTest'];
        }

        return $res;
    }
}
