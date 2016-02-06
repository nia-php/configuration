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
namespace Nia\Configuration\Reader\Json;

use ArrayIterator;
use Iterator;
use Nia\Collection\Map\StringMap\Map;
use Nia\Collection\Map\StringMap\MapInterface;
use Nia\Collection\Map\StringMap\ReadOnlyMap;
use Nia\Configuration\ConfigurationInterface;
use OutOfBoundsException;
use RuntimeException;

/**
 * Configuration for json files.
 */
class JsonFileConfiguration implements ConfigurationInterface
{
    use DotifyNestedMapTrait;

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
        $content = file_get_contents($file);

        $data = json_decode($content, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new RuntimeException(sprintf('Unable to read configuration from file "%s".', $file));
        }

        foreach ($data as $sectionName => $sectionData) {
            if (! is_array($sectionData)) {
                throw new RuntimeException(sprintf('The section "%s" is not a valid section.', $sectionName));
            }

            $sectionData = array_map('trim', $this->dotifyNestedMap($sectionData));

            $this->sections[$sectionName] = new ReadOnlyMap(new Map($this->dotifyNestedMap($sectionData)));
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
