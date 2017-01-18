<?php

namespace Bakame\Psr7\UriTest;

use Psr\Http\Message\UriInterface;
use PHPUnit\Framework\TestCase;
use InvalidArgumentException;

abstract class AbstractUriTestCase extends TestCase
{
    protected $uri_string = 'http://login:pass@secure.example.com:443/test/query.php?kingkong=toto#doc3';

    abstract protected function createUri($uri = '');

    protected function setUp()
    {
        $this->uri = $this->createUri($this->uri_string);
    }

    protected function tearDown()
    {
        $this->uri = null;
    }

    public function testGetterAccess()
    {
        $this->assertSame('http', $this->uri->getScheme());
        $this->assertSame('login:pass', $this->uri->getUserInfo());
        $this->assertSame('secure.example.com', $this->uri->getHost());
        $this->assertSame(443, $this->uri->getPort());
        $this->assertSame('login:pass@secure.example.com:443', $this->uri->getAuthority());
        $this->assertSame('/test/query.php', $this->uri->getPath());
        $this->assertSame('kingkong=toto', $this->uri->getQuery());
        $this->assertSame('doc3', $this->uri->getFragment());
    }

    public function testModifiedMethodsReturnsUriInterfaceInstance()
    {
        $this->assertInstanceOf(UriInterface::class, $this->uri->withPath('/test/query.php'));
        $this->assertInstanceOf(UriInterface::class, $this->uri->withFragment('doc3'));
    }

    /**
     * @dataProvider schemeProvider
     * @group scheme
     */
    public function testGetScheme($scheme, $expected)
    {
        $uri = $this->uri->withScheme($scheme);
        $this->assertInstanceOf(UriInterface::class, $uri);
        $this->assertSame($expected, $uri->getScheme(), 'Scheme must be normalized according to RFC3986');
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
        $uri = $this->uri->withUserInfo($user, $pass);
        $this->assertInstanceOf(UriInterface::class, $uri);
        $this->assertSame($expected, $uri->getUserInfo(), 'UserInfo must be normalized according to RFC3986');
    }

    public function userInfoProvider()
    {
        return [
            'with userinfo'  => ['iGoR', 'rAsMuZeN', 'iGoR:rAsMuZeN'],
            'no userinfo'    => ['', '', ''],
            'no pass'        => ['iGoR', '', 'iGoR'],
            'pass is null'   => ['iGoR', null, 'iGoR'],
            'case sensitive' => ['IgOr', 'RaSm0537', 'IgOr:RaSm0537'],
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
        $uri = $this->uri->withHost($host);
        $this->assertInstanceOf(UriInterface::class, $uri);
        $this->assertSame($expected, $uri->getHost(), 'Host must be normalized according to RFC3986');
    }

    public function hostProvider()
    {
        return [
            'normalized host' => ["MaStEr.eXaMpLe.CoM", "master.example.com"],
            "simple host"     => ["www.example.com", "www.example.com"],
            "IPv6 Host"       => ["[::1]", "[::1]"],
        ];
    }

    /**
     * @dataProvider portProvider
     */
    public function testPort($uri, $port, $expected)
    {
        $uri = $this->createUri($uri)->withPort($port);
        $this->assertInstanceOf(UriInterface::class, $uri);
        $this->assertSame($expected, $uri->getPort(), 'port must be an int or null');
    }

    public function portProvider()
    {
        return [
            ['http://www.example.com', 443, 443],
            ['http://www.example.com', 80, null],
            ['http://www.example.com', null, null],
            ['//www.example.com', 80, 80],
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
                ->createUri()
                ->withHost($host)
                ->withScheme($scheme)
                ->withUserInfo($user, $pass)
                ->withPort($port);
        $this->assertSame($authority, $uri->getAuthority());
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
     * @dataProvider queryProvider
     * @group query
     *
     * The value returned MUST be percent-encoded, but MUST NOT double-encode
     * any characters. To determine what characters to encode, please refer to
     * RFC 3986, Sections 2 and 3.4.
     */
    public function testGetQuery($query, $expected)
    {
        $uri = $this->uri->withQuery($query);
        $this->assertInstanceOf(UriInterface::class, $uri);
        $this->assertSame($expected, $uri->getQuery(), 'Query must be normalized according to RFC3986');
    }

    public function queryProvider()
    {
        return [
            'normalized query' => ['foo.bar=%7evalue', 'foo.bar=%7evalue'],
            'empty query'      => ['', ''],
            'same param query' => ['foo.bar=1&foo.bar=1', 'foo.bar=1&foo.bar=1'],
            'same param query' => ['?foo=1', '?foo=1'],
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
        $uri = $this->uri->withFragment($fragment);
        $this->assertInstanceOf(UriInterface::class, $uri);
        $this->assertSame($expected, $uri->getFragment(), 'Fragment must be normalized according to RFC3986');
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
        $uri = $this->createUri()
                ->withHost($host)
                ->withScheme($scheme)
                ->withUserInfo($user, $pass)
                ->withPort($port)
                ->withPath($path)
                ->withQuery($query)
                ->withFragment($fragment);
        $this->assertSame($expected, (string) $uri, 'URI string must be normalized according to RFC3986 rules');
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
                'uri'      => 'https://iGoR:rAsMuZeN@master.example.com/%7ejohndoe/%a1/index.php?foo.bar=%7evalue#fragment'
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
            'URL without authority and scheme' => [
                'scheme'   => '',
                'user'     => '',
                'pass'     => '',
                'host'     => '',
                'port'     => null,
                'path'     => '/foo/bar',
                'query'    => '',
                'fragment' => '',
                'uri'      => '/foo/bar',
            ],
        ];
    }

    public function testRemoveFragment()
    {
        $this->assertSame(
            'http://login:pass@secure.example.com:443/test/query.php?kingkong=toto',
            (string) $this->uri->withFragment('')
        );
    }

    public function testRemoveQuery()
    {
        $this->assertSame(
            'http://login:pass@secure.example.com:443/test/query.php#doc3',
            (string) $this->uri->withQuery('')
        );
    }

    public function testRemovePath()
    {
        $this->assertTrue(in_array((string) $this->uri->withPath(''), [
            'http://login:pass@secure.example.com:443?kingkong=toto#doc3',
            'http://login:pass@secure.example.com:443/?kingkong=toto#doc3',
        ]));
    }

    public function testRemovePort()
    {
        $this->assertSame(
            'http://login:pass@secure.example.com/test/query.php?kingkong=toto#doc3',
            (string) $this->uri->withPort(null));
    }

    public function testRemoveUserInfo()
    {
        $this->assertSame(
            'http://secure.example.com:443/test/query.php?kingkong=toto#doc3',
            (string) $this->uri->withUserInfo('')
        );
    }

    public function testRemoveScheme()
    {
        $this->assertSame(
            '//login:pass@secure.example.com:443/test/query.php?kingkong=toto#doc3',
            (string) $this->uri->withScheme('')
        );
    }

    public function testRemoveAuthority()
    {
        $uri_with_host = (string) $this->uri
            ->withScheme('')
            ->withUserInfo('')
            ->withPort(null)
            ->withHost('')
        ;

        $this->assertSame('/test/query.php?kingkong=toto#doc3', $uri_with_host);
    }

    /**
     * @dataProvider withSchemeFailedProvider
     */
    public function testWithSchemeFailed($scheme)
    {
        $this->expectException(InvalidArgumentException::class);
        $this->uri->withScheme($scheme);
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
    public function testWithUserInfoFailed($user, $pass)
    {
        $this->expectException(InvalidArgumentException::class);
        $this->uri->withUserInfo($user, $pass);
    }

    public function withUserInfoFailedProvider()
    {
        return [
            'invalid character in user :' => ['igo:r', 'rAsMuZeN'],
            'invalid character in user @' => ['igo@r', 'rAsMuZeN'],
            'invalid character in pass @' => ['iGoR', 'rasmu@sen'],
        ];
    }

    /**
     * @group host
     * @dataProvider withHostFailedProvider
     */
    public function testWithHostFailed($host)
    {
        $this->expectException(InvalidArgumentException::class);
        $this->uri->withHost($host);
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

    public function testWithPathFailedWithInvalidPathRelativeToTheAuthority()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->createUri('http://example.com')->withPath('foo/bar');
    }

    public function testWithPathFailedWithInvalidChars()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->createUri('http://example.com')->withPath('/?');
    }

    public function testWithQueryFailedWithInvalidChars()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->createUri('http://example.com')->withQuery('#');
    }

    public function testModificationFailedWithUnsupportedPort()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->createUri('http://example.com')->withPort(0);
    }

    public function testModificationFailedWithInvalidHost()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->createUri('http://example.com')->withHost('?');
    }

    /**
     * @dataProvider invalidURI
     */
    public function testCreateFromInvalidUrlKO($uri)
    {
        $this->expectException(InvalidArgumentException::class);
        $this->createUri($uri);
    }

    public function invalidURI()
    {
        return [
            ['http://user@:80'],
            [':'],
        ];
    }

    public function testModificationFailed()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->createUri('/path')->withScheme('http');
    }

    public function testEmptyValueDetection()
    {
        $expected = '//0:0@0/0?0#0';
        $this->assertSame($expected, (string) $this->createUri($expected));
    }

    public function testPathDetection()
    {
        $expected = 'foo/bar:';
        $this->assertSame($expected, $this->createUri($expected)->getPath());
    }
}