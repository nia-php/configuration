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
namespace Test\Nia\Configuration\Reader\Ini;

use PHPUnit_Framework_TestCase;
use Nia\Configuration\ConfigurationInterface;
use Nia\Configuration\Reader\Ini\IniFileConfiguration;
use RuntimeException;

/**
 * Unit test for \Nia\Configuration\Reader\Ini\IniFileConfiguration.
 */
class IniFileConfigurationTest extends PHPUnit_Framework_TestCase
{

    /** @var string */
    private $file = null;

    /** @var ConfigurationInterface */
    private $configuration = null;

    protected function setUp()
    {
        $content = <<<EOL

[database]
    hostname=127.0.0.1
    username=root
    password=
    port=1234

[environment]
    debug.mode=development
    debug.log=/var/log/application.log

EOL;
        $this->file = tempnam(sys_get_temp_dir(), 'unittest-');

        file_put_contents($this->file, $content);

        $this->configuration = new IniFileConfiguration($this->file);
    }

    protected function tearDown()
    {
        $this->configuration = null;
        unlink($this->file);
    }

    /**
     * @covers \Nia\Configuration\Reader\Ini\IniFileConfiguration::__construct
     */
    public function test__construct()
    {
        $this->setExpectedException(RuntimeException::class, 'Unable to read configuration from file "' . $this->file . '".');

        file_put_contents($this->file, '{"json-content":[]}');

        new IniFileConfiguration($this->file);
    }

    /**
     * @covers \Nia\Configuration\Reader\Ini\IniFileConfiguration::getSection
     */
    public function testGetSection()
    {
        $expectedDatabase = [
            'hostname' => '127.0.0.1',
            'username' => 'root',
            'password' => '',
            'port' => '1234'
        ];

        $expectedEnvironment = [
            'debug.mode' => 'development',
            'debug.log' => '/var/log/application.log'
        ];

        $this->assertEquals($expectedDatabase, iterator_to_array($this->configuration->getSection('database')));
        $this->assertEquals($expectedEnvironment, iterator_to_array($this->configuration->getSection('environment')));
    }

    /**
     * @covers \Nia\Configuration\Reader\Ini\IniFileConfiguration::getSection
     */
    public function testGetSectionException()
    {
        $this->setExpectedException(RuntimeException::class, 'Section "foobar" not found.');

        $this->configuration->getSection('foobar');
    }

    /**
     * @covers \Nia\Configuration\Reader\Ini\IniFileConfiguration::hasSection
     */
    public function testHasSection()
    {
        $this->assertSame(true, $this->configuration->hasSection('database'));
        $this->assertSame(true, $this->configuration->hasSection('environment'));
        $this->assertSame(false, $this->configuration->hasSection('foobar'));
    }

    /**
     * @covers \Nia\Configuration\Reader\Ini\IniFileConfiguration::getSectionsNames
     */
    public function testGetSectionsNames()
    {
        $expected = [
            'database',
            'environment'
        ];

        $this->assertEquals($expected, $this->configuration->getSectionsNames());
    }
}
