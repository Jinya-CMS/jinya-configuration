<?php

namespace Jinya\Configuration\Adapter;

class ArrayAdapter implements AdapterInterface
{
    /**
     * @param array<string, array<string, bool|int|string|null>|bool|int|string> $config
     */
    public function __construct(private array $config = [])
    {
    }

    /**
     * Gets the current configuration stored in the adapter, can be used to persist the data
     * @return array<string, array<string, bool|int|string|null>|bool|int|string>
     */
    public function getConfig(): array
    {
        return $this->config;
    }


    /**
     * @inheritDoc
     */
    public function get(string $key, ?string $group = null, bool|int|string|null $default = null): string|bool|int|null
    {
        if (!$group) {
            /** @phpstan-ignore-next-line */
            return $this->config[$key] ?? $default;
        }

        $data = $this->config[$group] ?? [$key => $default];

        return $data[$key] ?? $default;
    }

    /**
     * @inheritDoc
     */
    public function getAll(?string $group = null): array
    {
        if (!$group) {
            return array_filter($this->config, static fn (mixed $data) => !is_array($data));
        }

        /** @phpstan-ignore-next-line */
        return $this->config[$group] ?? [];
    }

    /**
     * @inheritDoc
     */
    public function set(string $key, bool|int|string $value, ?string $group = null): void
    {
        if ($group) {
            if (!$this->config[$group]) {
                $this->config[$group] = [$key => $value];
            } else {
                /** @phpstan-ignore-next-line */
                $this->config[$group][$key] = $value;
            }
        } else {
            $this->config[$key] = $value;
        }
    }

    /**
     * @inheritDoc
     */
    public function delete(string $key, ?string $group = null): void
    {
        if ($group && array_key_exists($group, $this->config) && is_array($this->config[$group])) {
            unset($this->config[$group][$key]);
        } elseif (array_key_exists($key, $this->config) && !is_array($this->config[$key])) {
            unset($this->config[$key]);
        }
    }
}
