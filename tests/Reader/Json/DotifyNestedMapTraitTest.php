<?php
/*
 * This file is part of the nia framework architecture.
 *
 * (c) 2016 - Patrick Ullmann <patrick.ullmann@nat-software.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types = 1);
namespace Test\Nia\Configuration\Reader\Json;

use PHPUnit_Framework_TestCase;
use Nia\Configuration\Reader\Json\DotifyNestedMapTrait;

/**
 * Unit test for \Nia\Configuration\Reader\Json\DotifyNestedMapTrait.
 */
class DotifyNestedMapTraitTest extends PHPUnit_Framework_TestCase
{

    /**
     * @covers \Nia\Configuration\Reader\Json\DotifyNestedMapTrait::dotifyNestedMap
     */
    public function testDotifyNestedMap()
    {
        $trait = new class() {
            use DotifyNestedMapTrait;

            public function call(array $map): array
            {
                return $this->dotifyNestedMap($map);
            }
        };

        $map = [
            'database' => [
                'hostname' => '127.0.0.1',
                'username' => 'root'
            ],
            'debug' => 'true'
        ];

        $expected = [
            'database.hostname' => '127.0.0.1',
            'database.username' => 'root',
            'debug' => 'true'
        ];

        $this->assertEquals($expected, $trait->call($map));
    }
}
