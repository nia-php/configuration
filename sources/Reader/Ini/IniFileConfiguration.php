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
namespace Nia\Configuration\Reader\Ini;

use ArrayIterator;
use Iterator;
use Nia\Collection\Map\StringMap\Map;
use Nia\Collection\Map\StringMap\MapInterface;
use Nia\Collection\Map\StringMap\ReadOnlyMap;
use Nia\Configuration\ConfigurationInterface;
use OutOfBoundsException;
use RuntimeException;

/**
 * Configuration reader for ini files.
 */
class IniFileConfiguration implements ConfigurationInterface
{

    /**
     * Native map with sections and section data maps.
     *
     * @var MapInterface[]
     */
    private $sections = [];

    /**
     * Constructor.
     *
     * @param string $file
     *            Path to configuration file.
     * @throws RuntimeException If the configuration is unable to read.
     */
    public function __construct(string $file)
    {
        // try/catch because if non-valid ini data is passed to parse_ini_file
        // it will generate a warning which could be catched using an error handler.
        // "PHP Warning: syntax error, unexpected '?' in Unknown on line 1"
        try {
            $data = parse_ini_file($file, true, INI_SCANNER_RAW);
        } catch (\Exception $exception) {
            $data = false;
        }

        if ($data === false) {
            throw new RuntimeException(sprintf('Unable to read configuration from file "%s".', $file));
        }

        foreach ($data as $sectionName => $sectionData) {
            $this->sections[$sectionName] = new ReadOnlyMap(new Map($sectionData));
        }
    }

    /**
     *
     * {@inheritDoc}
     *
     * @see \Nia\Configuration\ConfigurationInterface::getSection($sectionName)
     */
    public function getSection(string $sectionName): MapInterface
    {
        if (! $this->hasSection($sectionName)) {
            throw new OutOfBoundsException(sprintf('Section "%s" not found.', $sectionName));
        }

        return $this->sections[$sectionName];
    }

    /**
     *
     * {@inheritDoc}
     *
     * @see \Nia\Configuration\ConfigurationInterface::hasSection($sectionName)
     */
    public function hasSection(string $sectionName): bool
    {
        return array_key_exists($sectionName, $this->sections);
    }

    /**
     *
     * {@inheritDoc}
     *
     * @see \Nia\Configuration\ConfigurationInterface::getSectionsNames()
     */
    public function getSectionsNames(): array
    {
        return array_keys($this->sections);
    }

    /**
     *
     * {@inheritDoc}
     *
     * @see IteratorAggregate::getIterator()
     */
    public function getIterator(): Iterator
    {
        return new ArrayIterator($this->sections);
    }
}
