<?php

namespace Jinya\Configuration\Adapter;

use Jinya\Configuration\Adapter\Exceptions\DeleteNotSupportedException;
use Jinya\Configuration\Adapter\Exceptions\SetNotSupportedException;

interface AdapterInterface
{
    /**
     * Gets the setting with the given key in the given group
     *
     * @param string $key The key to look for
     * @param string|null $group The group to look for
     * @param string|bool|int|null $default The default value to return if no value is set
     */
    public function get(string $key, ?string $group = null, string|bool|int|null $default = null): string|bool|int|null;

    /**
     * Gets all settings within the given group
     *
     * @param string|null $group The group to look for
     * @return array<string, string|bool|int|null>
     */
    public function getAll(?string $group = null): array;

    /**
     * Sets the given value in the given key under the given group
     *
     * @param string $key The key to set
     * @param string|bool|int $value The value to set
     * @param string|null $group The group to set in
     *
     * @throws SetNotSupportedException
     */
    public function set(string $key, string|bool|int $value, ?string $group = null): void;

    /**
     * Deletes the given key in the given group
     *
     * @param string $key The key to delete
     * @param string|null $group The group to delete the key in
     *
     * @throws DeleteNotSupportedException
     */
    public function delete(string $key, ?string $group = null): void;
}
