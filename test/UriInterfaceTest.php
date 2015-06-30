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
    public function testStringRepresentation($url, $classname1, $classname2)
    {
        $obj1 = (new ReflectionClass("Psr7\UriInterface\Testsuite\\".$classname1))->newInstance();
        $obj2 = (new ReflectionClass("Psr7\UriInterface\Testsuite\\".$classname2))->newInstance();
        $this->assertSame($obj1->createUriObject($url)->__toString(), $obj2->createUriObject($url)->__toString());
    }

    public function urlProvider()
    {
        $urlList = [
            'http://www.example.com',
            'http:/example.com',
            'http:example.com',
            'http://WwW.ExAmPlE.CoM',
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
