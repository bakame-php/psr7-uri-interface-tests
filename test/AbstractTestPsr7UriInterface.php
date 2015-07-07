<?php

namespace Psr7\UriInterface\Testsuite;

use PHPUnit_Framework_TestCase;

abstract class AbstractTestPsr7UriInterface extends PHPUnit_Framework_TestCase
{
    /**
     * @return Psr\Http\Message\UriInterface
     */
    abstract public function createUriObject($url);

    /**
     * @dataProvider uriProvider
     * @group scheme
     */
    public function testGetScheme($url, $scheme)
    {
        $url = $this->createUriObject($url);
        $this->assertSame($scheme, $url->getScheme());
    }

    /**
     * @dataProvider uriProvider
     * @group userinfo
     */
    public function testGetUserInfo($url, $scheme, $userinfo)
    {
        $url = $this->createUriObject($url);
        $this->assertSame($userinfo, $url->getUserInfo());
    }

    /**
     * @dataProvider uriProvider
     * @group host
     *
     * Host MUST be normalized to lowercase if present
     */
    public function testGetHost($url, $scheme, $userinfo, $host)
    {
        $url = $this->createUriObject($url);
        $this->assertSame($host, $url->getHost());
    }

    /**
     * @dataProvider uriProvider
     * @group port
     *
     * If no port is present and no scheme is present, this method MUST return a null value
     * If no port is present but a scheme is present, this method MAY return the standard port, but SHOULD return null
     */
    public function testGetPort($url, $scheme, $userinfo, $host, $port)
    {
        $url = $this->createUriObject($url);
        $this->assertContains($url->getPort(), [null, $port]);
    }

    /**
     * @dataProvider uriProvider
     * @group authority
     *
     * If the port component is not set or is the standard port for the current
     * scheme, it SHOULD NOT be included.
     */
    public function testGetAuthority($url, $scheme, $userinfo, $host, $port, $authority)
    {
        $url = $this->createUriObject($url);
        $this->assertSame($authority, $url->getAuthority());
    }

    /**
     * @dataProvider uriProvider
     * @group path
     *
     * The value returned MUST be percent-encoded, but MUST NOT double-encode
     * any characters. To determine what characters to encode, please refer to
     * RFC 3986, Sections 2 and 3.3.
     */
    public function testGetPath($url, $scheme, $userinfo, $host, $port, $authority, $path)
    {
        $url = $this->createUriObject($url);
        $this->assertSame($path, $url->getPath());
    }

    /**
     * @dataProvider uriProvider
     * @group query
     *
     * The value returned MUST be percent-encoded, but MUST NOT double-encode
     * any characters. To determine what characters to encode, please refer to
     * RFC 3986, Sections 2 and 3.4.
     */
    public function testGetQuery(
        $url,
        $scheme,
        $userinfo,
        $host,
        $port,
        $authority,
        $path,
        $query,
        $fragment,
        $normalized
    ) {
        $url = $this->createUriObject($url);
        $this->assertSame($query, $url->getQuery());
    }

    /**
     * @dataProvider uriProvider
     * @group fragment
     *
     * The value returned MUST be percent-encoded, but MUST NOT double-encode
     * any characters. To determine what characters to encode, please refer to
     * RFC 3986, Sections 2 and 3.5.
     */
    public function testGetFragment(
        $url,
        $scheme,
        $userinfo,
        $host,
        $port,
        $authority,
        $path,
        $query,
        $fragment,
        $normalized
    ) {
        $url = $this->createUriObject($url);
        $this->assertSame($fragment, $url->getFragment());
    }

    /**
     * @dataProvider uriProvider
     * @group toString
     *
     * - If a scheme is present, it MUST be suffixed by ":".
     * - If an authority is present, it MUST be prefixed by "//".
     * - The path can be concatenated without delimiters. But there are two
     *   cases where the path has to be adjusted to make the URI reference
     *   valid as PHP does not allow to throw an exception in __toString():
     *     - If the path is rootless and an authority is present, the path MUST
     *       be prefixed by "/".
     *     - If the path is starting with more than one "/" and no authority is
     *       present, the starting slashes MUST be reduced to one.
     * - If a query is present, it MUST be prefixed by "?".
     * - If a fragment is present, it MUST be prefixed by "#".
     */
    public function testToString(
        $url,
        $scheme,
        $userinfo,
        $host,
        $port,
        $authority,
        $path,
        $query,
        $fragment,
        $normalized
    ) {
        $url = $this->createUriObject($url);
        $this->assertSame($normalized, $url->__toString());
    }

    /**
     * @dataProvider uriProvider
     * @group scheme
     */
    public function testWithScheme($url)
    {
        $url = $this->createUriObject($url);
        $this->assertEquals($url->getScheme(), $url->withScheme($url->getScheme())->getScheme());
    }

    /**
     * @dataProvider uriProvider
     * @group userinfo
     */
    public function testWithUserInfo($url)
    {
        $url  = $this->createUriObject($url);
        $res  = explode(':', $url->getUserInfo());
        $user = array_shift($res);
        $pass = array_shift($res);

        $this->assertEquals($url->getUserInfo(), $url->withUserInfo($user, $pass)->getUserInfo());
    }

    /**
     * @dataProvider uriProvider
     * @group host
     */
    public function testWithHost($url)
    {
        $url = $this->createUriObject($url);
        $this->assertEquals($url->getHost(), $url->withHost($url->getHost())->getHost());
    }

    /**
     * @dataProvider uriProvider
     * @group port
     */
    public function testWithPort($url)
    {
        $url = $this->createUriObject($url);
        $this->assertContains($url->withPort($url->getPort())->getPort(), [null, $url->getPort()]);
    }

    /**
     * @dataProvider uriProvider
     * @group path
     */
    public function testWithPath($url)
    {
        $url = $this->createUriObject($url);
        $this->assertSame($url->getPath(), $url->withPath($url->getPath())->getPath());
    }

    /**
     * @dataProvider uriProvider
     * @group query
     */
    public function testWithQuery($url)
    {
        $url = $this->createUriObject($url);
        $this->assertEquals($url->getQuery(), $url->withQuery($url->getQuery())->getQuery());
    }

    /**
     * @dataProvider uriProvider
     * @group fragment
     */
    public function testWithFragment($url)
    {
        $url = $this->createUriObject($url);
        $this->assertEquals($url->getFragment(), $url->withFragment($url->getFragment())->getFragment());
    }

    public function uriProvider()
    {
        return [
            'URL with full components' => [
                "HtTpS://igor:rasmusen@MaStEr.eXaMpLe.CoM:443/%7ejohndoe/%a1/index.php?foo.bar=value#fragment",
                "https",
                "igor:rasmusen",
                "master.example.com",
                443,
                "igor:rasmusen@master.example.com",
                "/~johndoe/%A1/index.php",
                "foo.bar=value",
                "fragment",
                "https://igor:rasmusen@master.example.com/~johndoe/%A1/index.php?foo.bar=value#fragment"
            ],
            "non standard port" => [
                "http://www.example.com:443/",
                "http",
                "",
                "www.example.com",
                443,
                "www.example.com:443",
                "/",
                "",
                "",
                "http://www.example.com:443/",
            ],
            "IDN hostname" => [
                "https://مثال.إختبار:81/foo/bar.php",
                "https",
                "",
                "مثال.إختبار",
                81,
                "مثال.إختبار:81",
                "/foo/bar.php",
                "",
                "",
                "https://مثال.إختبار:81/foo/bar.php",
            ]/*
            "scheme + rootless path and no authority" => [
                "http:example.com",
                "http",
                "",
                "",
                null,
                "",
                "example.com",
                "",
                "",
                "http:example.com",
            ],
            "scheme + absolute path and no authority" => [
                "http:/example.com",
                "http",
                "",
                "",
                null,
                "",
                "/example.com",
                "",
                "",
                "http:/example.com",
            ],*/
        ];
    }
}
