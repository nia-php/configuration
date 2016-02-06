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
namespace Nia\Configuration;

use IteratorAggregate;
use Nia\Collection\Map\StringMap\MapInterface;
use OutOfBoundsException;

/**
 * Interface for all configuration implementations.
 */
interface ConfigurationInterface extends IteratorAggregate
{

    /**
     * Returns the data of a requested section as a map.
     *
     * @param string $sectionName
     *            Name of the requested section.
     * @throws OutOfBoundsException If the section does not exist.
     * @return MapInterface The data of a requested section as a map.
     */
    public function getSection(string $sectionName): MapInterface;

    /**
     * Checks whether a section exists.
     *
     * @param string $sectionName
     *            Name of the section to check for.
     * @return bool Returns 'true' if the section exist, otherwise 'false' will be returned.
     */
    public function hasSection(string $sectionName): bool;

    /**
     * Returns a list of section names.
     *
     * @return string[] List of section names.
     */
    public function getSectionsNames(): array;
}
