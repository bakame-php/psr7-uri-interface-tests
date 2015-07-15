<?php

namespace Psr7\UriInterface\Testsuite;

use PHPUnit_Framework_Assert as Assert;
use Psr\Http\Message\UriInterface;

trait UriInterfaceTestsTrait
{
    /**
     * @return UriInterface
     */
    abstract public function createDefaultUri();

    public function testUriImplementsInterface()
    {
        Assert::assertInstanceOf('Psr\Http\Message\UriInterface', $this->createDefaultUri());
    }

    /**
     * @dataProvider schemeProvider
     * @group scheme
     */
    public function testGetScheme($scheme, $expected)
    {
        $uri = $this->createDefaultUri()->withScheme($scheme);
        Assert::assertEquals($expected, $uri->getScheme(), 'Scheme must be normalized according to RFC3986');
    }

    public function schemeProvider()
    {
        return [
            'normalized scheme' => ['HtTpS', 'https'],
            'simple scheme'     => ['http', 'http'],
            'no scheme'         => ['',''],
        ];
    }

    /**
     * @dataProvider userInfoProvider
     * @group userinfo
     */
    public function testGetUserInfo($user, $pass, $expected)
    {
        $uri = $this->createDefaultUri()->withUserInfo($user, $pass);
        Assert::assertEquals($expected, $uri->getUserInfo());
    }

    public function userInfoProvider()
    {
        return [
            'with userinfo' => ['iGoR', 'rAsMuZeN', 'iGoR:rAsMuZeN'],
            'no userinfo'   => ['', '', ''],
            'no pass'       => ['iGoR', '', 'iGoR'],
            'pass is null'  => ['iGoR', null, 'iGoR'],
            'upercased'     => ['IgOr', 'RaSm0537', 'IgOr:RaSm0537'],
        ];
    }

    /**
     * @dataProvider hostProvider
     * @group host
     *
     * Host MUST be normalized to lowercase if present
     */
    public function testGetHost($host, $expected)
    {
        $uri = $this->createDefaultUri()->withHost($host);
        Assert::assertEquals($expected, $uri->getHost(), 'Host must be normalized according to RFC3986');
    }

    public function hostProvider()
    {
        return [
            'normalized host' => ["MaStEr.eXaMpLe.CoM", "master.example.com"],
            "simple host"     => ["www.example.com", "www.example.com"],
            "IDN hostname"    => ["مثال.إختبار", "مثال.إختبار"],
            "IPv6 Host"       => ["[::1]", "[::1]"],
        ];
    }

    /**
     * @dataProvider portProvider
     * @group port
     *
     * If no port is present and no scheme is present, this method MUST return a null value
     * If no port is present but a scheme is present, this method MAY return the standard port, but SHOULD return null
     */
    public function testGetPort($port, $scheme, $host, $expected)
    {
        $uri = $this->createDefaultUri()->withHost($host)->withScheme($scheme)->withPort($port);
        Assert::assertContains($uri->getPort(), [(int) $expected, null], 'port must be an int or null');
    }

    public function portProvider()
    {
        return [
            'non standard string port' => ['443', 'http', 'localhost', 443],
            "non standard int port"    => [443,   'http', 'localhost', 443],
            "no port"                  => [null,  'http', 'localhost', null],
            "standard port"            => [80,    'http', 'localhost', 80],
        ];
    }

    /**
     * @dataProvider authorityProvider
     * @group authority
     *
     * If the port component is not set or is the standard port for the current
     * scheme, it SHOULD NOT be included.
     */
    public function testGetAuthority($scheme, $user, $pass, $host, $port, $authority)
    {
        $uri = $this
                ->createDefaultUri()
                ->withHost($host)
                ->withScheme($scheme)
                ->withUserInfo($user, $pass)
                ->withPort($port);

        Assert::assertEquals($authority, $uri->getAuthority());
    }

    public function authorityProvider()
    {
        return [
            'authority' => [
                'scheme'    => 'http',
                'user'      => 'iGoR',
                'pass'      => 'rAsMuZeN',
                'host'      => 'master.example.com',
                'port'      => 443,
                'authority' => 'iGoR:rAsMuZeN@master.example.com:443',
            ],
            'without port' => [
                'scheme'    => 'http',
                'user'      => 'iGoR',
                'pass'      => 'rAsMuZeN',
                'host'      => 'master.example.com',
                'port'      => null,
                'authority' => 'iGoR:rAsMuZeN@master.example.com',
            ],
            'with standard port' => [
                'scheme'    => 'http',
                'user'      => 'iGoR',
                'pass'      => 'rAsMuZeN',
                'host'      => 'master.example.com',
                'port'      => 80,
                'authority' => 'iGoR:rAsMuZeN@master.example.com',
            ],
            "authority without pass" => [
                'scheme'    => 'http',
                'user'      => 'iGoR',
                'pass'      => '',
                'host'      => 'master.example.com',
                'port'      => null,
                'authority' => 'iGoR@master.example.com',
            ],
            "authority without port and userinfo" => [
                'scheme'    => 'http',
                'user'      => '',
                'pass'      => '',
                'host'      => 'master.example.com',
                'port'      => null,
                'authority' => 'master.example.com',
            ],
        ];
    }

    /**
     * @dataProvider pathProvider
     * @group path
     *
     * The value returned MUST be percent-encoded, but MUST NOT double-encode
     * any characters. To determine what characters to encode, please refer to
     * RFC 3986, Sections 2 and 3.3.
     */
    public function testGetPath($path, $expected)
    {
        $uri = $this->createDefaultUri()->withPath($path);
        Assert::assertEquals($expected, $uri->getPath(), 'Path must be normalized according to RFC3986');
    }

    public function pathProvider()
    {
        return [
            'normalized path'         => ['/%7ejohndoe/%a1/index.php', '/~johndoe/%A1/index.php'],
            'slash forward only path' => ['/', '/'],
            'empty path'              => ['', ''],
        ];
    }

    /**
     * @dataProvider queryProvider
     * @group query
     *
     * The value returned MUST be percent-encoded, but MUST NOT double-encode
     * any characters. To determine what characters to encode, please refer to
     * RFC 3986, Sections 2 and 3.4.
     */
    public function testGetQuery($query, $expected)
    {
        $uri = $this->createDefaultUri()->withQuery($query);
        Assert::assertEquals($expected, $uri->getQuery(), 'Query must be normalized according to RFC3986');
    }

    public function queryProvider()
    {
        return [
            'normalized query' => ['foo.bar=%7evalue', 'foo.bar=~value'],
            'empty query'      => ['', ''],
            'same param query' => ['foo.bar=1&foo.bar=1', 'foo.bar=1&foo.bar=1'],
            'same param query' => ['?foo=1', '%3Ffoo=1'],
        ];
    }
    /**
     * @dataProvider fragmentProvider
     * @group fragment
     *
     * The value returned MUST be percent-encoded, but MUST NOT double-encode
     * any characters. To determine what characters to encode, please refer to
     * RFC 3986, Sections 2 and 3.5.
     */
    public function testGetFragment($fragment, $expected)
    {
        $uri = $this->createDefaultUri()->withFragment($fragment);
        Assert::assertEquals($expected, $uri->getFragment(), 'Fragment must be normalized according to RFC3986');
    }

    public function fragmentProvider()
    {
        return [
            'URL with full components'        => ['fragment', 'fragment'],
            'URL with non-encodable fragment' => ["azAZ0-9/?-._~!$&'()*+,;=:@", "azAZ0-9/?-._~!$&'()*+,;=:@"],
        ];
    }

    /**
     * @dataProvider stringProvider
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
    public function testToString($scheme, $user, $pass, $host, $port, $path, $query, $fragment, $expected)
    {
        $uri = $this->createDefaultUri()
                ->withHost($host)
                ->withScheme($scheme)
                ->withUserInfo($user, $pass)
                ->withPort($port)
                ->withPath($path)
                ->withQuery($query)
                ->withFragment($fragment);

        Assert::assertEquals($expected, (string) $uri, 'URI string must be normalized according to RFC3986 rules');
    }

    public function stringProvider()
    {
        return [
            'URL normalized' => [
                'scheme'   => 'HtTps',
                'user'     => 'iGoR',
                'pass'     => 'rAsMuZeN',
                'host'     => 'MaStEr.eXaMpLe.CoM',
                'port'     => 443,
                'path'     => '/%7ejohndoe/%a1/index.php',
                'query'    => 'foo.bar=%7evalue',
                'fragment' => 'fragment',
                'uri'      => 'https://iGoR:rAsMuZeN@master.example.com/~johndoe/%A1/index.php?foo.bar=~value#fragment'
            ],
            'URL without scheme' => [
                'scheme'   => '',
                'user'     => '',
                'pass'     => '',
                'host'     => 'www.example.com',
                'port'     => 443,
                'path'     => '/foo/bar',
                'query'    => 'param=value',
                'fragment' => 'fragment',
                'uri'      => '//www.example.com:443/foo/bar?param=value#fragment',
            ],
            'URL without rootless path' => [
                'scheme'   => 'http',
                'user'     => '',
                'pass'     => '',
                'host'     => 'www.example.com',
                'port'     => null,
                'path'     => 'foo/bar',
                'query'    => '',
                'fragment' => '',
                'uri'      => 'http://www.example.com/foo/bar',
            ],
            'URL without authority and scheme' => [
                'scheme'   => '',
                'user'     => '',
                'pass'     => '',
                'host'     => '',
                'port'     => null,
                'path'     => '//foo/bar',
                'query'    => '',
                'fragment' => '',
                'uri'      => '/foo/bar',
            ],
        ];
    }

    /**
     * @group scheme
     * @dataProvider withSchemeFailedProvider
     * @expectedException InvalidArgumentException
     */
    public function testWithSchemeFailed($scheme)
    {
        $this->createDefaultUri()->withScheme($scheme);
    }

    public function withSchemeFailedProvider()
    {
        return [
            'invalid char'         => ['in,valid'],
            'integer like string'  => ['123'],
            'unknown scheme'       => ['yolo'],
        ];
    }


    /**
     * @group userinfo
     * @dataProvider withUserInfoFailedProvider
     * @expectedException InvalidArgumentException
     */
    public function testWithUserInfoFailed($user, $pass, $host)
    {
        $this->createDefaultUri()->withHost($host)->withUserInfo($user, $pass);
    }

    public function withUserInfoFailedProvider()
    {
        return [
            'invalid character in user :' => ['igo:r', 'rAsMuZeN', 'example.com'],
            'invalid character in user @' => ['igo@r', 'rAsMuZeN', 'example.com'],
            'invalid character in pass'   => ['iGoR', 'rasmu@sen', 'example.com'],
            'bool in user'                => [true, 'rAsMuZeN', 'example.com'],
            'bool in pass'                => ['iGoR', true, 'example.com'],
            'Std Class in user'           => [(object) 'iGoR', 'rAsMuZeN', 'example.com'],
            'Std Class in pass'           => ['iGoR', (object) 'rAsMuZeN', 'example.com'],
            'array in user'               => [['iGoR'], 'rAsMuZeN', 'example.com'],
            'array in pass'               => ['iGoR', ['rAsMuZeN'], 'example.com'],
        ];
    }

    /**
     * @group host
     * @dataProvider withHostFailedProvider
     * @expectedException InvalidArgumentException
     */
    public function testWithHostFailed($host)
    {
        $this->createDefaultUri()->withHost($host);
    }

    public function withHostFailedProvider()
    {
        return [
            'dot in front'                         => ['.example.com'],
            'hyphen suffix'                        => ['host.com-'],
            'multiple dot'                         => ['.......'],
            'one dot'                              => ['.'],
            'empty label'                          => ['tot.    .coucou.com'],
            'space in the label'                   => ['re view'],
            'underscore in label'                  => ['_bad.host.com'],
            'label too long'                       => [implode('', array_fill(0, 12, 'banana')).'.secure.example.com'],
            'too many labels'                      => [implode('.', array_fill(0, 128, 'a'))],
            'Invalid IPv4 format'                  => ['[127.0.0.1]'],
            'Invalid IPv6 format'                  => ['[[::1]]'],
            'Invalid IPv6 format 2'                => ['[::1'],
            'space character in starting label'    => ['example. com'],
            'invalid character in host label'      => ["examp\0le.com"],
            'invalid IP with scope'                => ['[127.2.0.1%253]'],
            'invalid scope IPv6'                   => ['ab23::1234%251'],
            'invalid scope ID'                     => ['fe80::1234%25?@'],
            'invalid scope ID with utf8 character' => ['fe80::1234%25€'],
        ];
    }

    /**
     * @group port
     * @dataProvider withPortFailedProvider
     * @expectedException InvalidArgumentException
     */
    public function testWithPortFailed($port, $host)
    {
        $this->createDefaultUri()->withHost($host)->withPort($port);
    }

    public function withPortFailedProvider()
    {
        return [
            'string'                       => ['toto', 'localhost'],
            'invalid port number too low'  => ['-23', 'localhost'],
            'invalid port number too high' => ['10000000', 'localhost'],
            'invalid port number'          => ['0', 'localhost'],
            'float'                        => [1.2, 'localhost'],
            'array'                        => [['foo'], 'localhost'],
        ];
    }

    /**
     * @group path
     * @dataProvider withPathFailedProvider
     * @expectedException InvalidArgumentException
     */
    public function withPathFailed($path)
    {
        $this->setExpectedException('InvalidArgumentException');
        $this->createDefaultUri()->withPath($path);
    }

    public function withPathFailedProvider()
    {
        return [
            'invalid ? character' => ['foo?bar'],
            'invalid # character' => ['foo#bar'],
            'Std Class'           => [(object) 'foo'],
            'array'               => [['foo']],
        ];
    }

    /**
     * @group query
     */
    public function withQueryFailed()
    {
        $this->setExpectedException('InvalidArgumentException');
        $this->createDefaultUri()->withQuery('bar#toto');
    }
}
