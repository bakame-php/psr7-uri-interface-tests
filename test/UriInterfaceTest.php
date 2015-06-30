<?php

namespace Psr7\UriInterface\Testsuite;

use PHPUnit_Framework_TestCase;
use ReflectionClass;

/**
 * @group compareUri
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
            'http://www.example.com',
            'http:/example.com',
            'http:example.com',
            'http://WwW.ExAmPlE.CoM',
            "HtTpS://igor:rasmusen@MaStEr.eXaMpLe.CoM:443/%7ejohndoe/%a1/index.php?foo.bar=value#fragment",
        ];

        $res = [];
        foreach ($urlList as $url) {
            $res["league-league-$url"]       = [$url, 'LeagueUrlTest', 'LeagueUrlTest'];
            $res["league-guzzle-$url"]       = [$url, 'LeagueUrlTest', 'GuzzleTest'];
            $res["league-diactoros-$url"]    = [$url, 'LeagueUrlTest', 'DiactorosTest'];
            $res["league-slim-$url"]         = [$url, 'LeagueUrlTest', 'SlimTest'];
            $res["guzzle-guzzle-$url"]       = [$url, 'GuzzleTest', 'GuzzleTest'];
            $res["guzzle-diactoros-$url"]    = [$url, 'GuzzleTest', 'DiactorosTest'];
            $res["guzzle-slim-$url"]         = [$url, 'GuzzleTest', 'SlimTest'];
            $res["diactoros-diactoros-$url"] = [$url, 'DiactorosTest', 'DiactorosTest'];
            $res["diactoros-slim-$url"]      = [$url, 'DiactorosTest', 'SlimTest'];
            $res["slim-slim-$url"]           = [$url, 'SlimTest', 'SlimTest'];
        }

        return $res;
    }
}
