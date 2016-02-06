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

use RecursiveArrayIterator;
use RecursiveIteratorIterator;

/**
 * Converts a native array to a dot notation.
 */
trait DotifyNestedMapTrait
{

    /**
     * Dotifies a nested map.
     *
     * @param string[] $data
     *            The nested native map to dotify.
     * @return string[] The dotified map.
     */
    private function dotifyNestedMap(array $data): array
    {
        $result = [];

        $iterator = new RecursiveIteratorIterator(new RecursiveArrayIterator($data));
        foreach ($iterator as $value) {
            $keys = [];
            foreach (range(0, $iterator->getDepth()) as $depth) {
                $keys[] = $iterator->getSubIterator($depth)->key();
            }

            $key = implode('.', $keys);
            $result[$key] = $value;
        }

        return $result;
    }
}
