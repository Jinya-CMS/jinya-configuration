<?php

namespace Jinya\Configuration\Adapter;

use Jinya\Configuration\Adapter\Exceptions\DeleteNotSupportedException;
use Jinya\Configuration\Adapter\Exceptions\SetNotSupportedException;

class IniAdapter implements AdapterInterface
{
    /**
     * @var array<string, array<string, bool|int|string|null>|bool|int|string|null>
     */
    private array $parsedConfig;

    public function __construct(private readonly string $configFile)
    {
        $this->parsedConfig = $this->parseIni();
    }

    private function parseIni(): array
    {
        return parse_ini_file($this->configFile, true, INI_SCANNER_TYPED);
    }

    /**
     * {@inheritDoc}
     */
    public function get(string $key, ?string $group = null, bool|int|string|null $default = null): string|bool|int|null
    {
        if (!$group) {
            return $this->parsedConfig[$key] ?? $default;
        }

        $data = $this->parsedConfig[$group] ?? [$key => $default];

        return $data[$key] ?? $default;
    }

    /**
     * {@inheritDoc}
     */
    public function getAll(?string $group = null): array
    {
        if (!$group) {
            return array_filter($this->parsedConfig, static fn (mixed $data) => !is_array($data));
        }

        return $this->parsedConfig[$group] ?? [];
    }

    /**
     * {@inheritDoc}
     */
    public function set(string $key, bool|int|string $value, ?string $group = null): void
    {
        throw new SetNotSupportedException();
    }

    /**
     * {@inheritDoc}
     */
    public function delete(string $key, ?string $group = null): void
    {
        throw new DeleteNotSupportedException();
    }
}
