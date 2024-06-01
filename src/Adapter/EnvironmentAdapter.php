<?php

namespace Jinya\Configuration\Adapter;

class EnvironmentAdapter implements AdapterInterface
{
    /**
     * @inheritDoc
     */
    public function get(string $key, ?string $group = null, bool|int|string|null $default = null): string|bool|int|null
    {
        if ($group) {
            $key = "{$group}_$key";
        }
        $key = strtoupper($key);

        return getenv($key) ?: $default;
    }

    /**
     * @inheritDoc
     */
    public function getAll(?string $group = null): array
    {
        $env = getenv();
        if ($group) {
            $group = strtoupper($group) . '_';

            $data = array_filter($env, static fn (string $key) => str_starts_with($key, $group), ARRAY_FILTER_USE_KEY);
            $result = [];
            foreach ($data as $key => $value) {
                $lowerKeyNoPrefix = ltrim(strtolower($key), $group);
                $result[$lowerKeyNoPrefix] = $value;
            }

            return $data;
        }

        return $env;
    }

    /**
     * @inheritDoc
     */
    public function set(string $key, bool|int|string $value, ?string $group = null): void
    {
        if ($group) {
            $key = "{$group}_$key";
        }
        $key = strtoupper($key);
        $data = "$key=$value";

        putenv($data);
    }

    /**
     * @inheritDoc
     */
    public function delete(string $key, ?string $group = null): void
    {
        if ($group) {
            $key = "{$group}_$key";
        }
        $key = strtoupper($key);

        putenv($key);
    }
}
