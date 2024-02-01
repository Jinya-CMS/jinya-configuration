<?php

namespace Jinya\Configuration;

use Jinya\Configuration\Adapter\AdapterInterface;
use Jinya\Configuration\Adapter\Exceptions\DeleteNotSupportedException;
use Jinya\Configuration\Adapter\Exceptions\SetNotSupportedException;

class Configuration implements AdapterInterface
{
    /**
     * @param AdapterInterface[] $priorityList
     */
    public function __construct(private array $priorityList = [])
    {
    }

    /**
     * Adds a new adapter with the given priority to the list of adapters
     *
     * @param AdapterInterface $adapter
     * @param int|null $priority
     * @return void
     */
    public function addAdapter(AdapterInterface $adapter, int|null $priority = null): void
    {
        if ($priority === null) {
            $this->priorityList[] = $adapter;
        } else {
            array_splice($this->priorityList, $priority, length: 0, replacement: [$adapter]);
        }
    }

    /**
     * @inheritDoc
     */
    public function get(string $key, ?string $group = null, bool|int|string|null $default = null): string|bool|int|null
    {
        foreach ($this->priorityList as $item) {
            $result = $item->get($key, $group);
            if ($result !== null) {
                return $result;
            }
        }

        return $default;
    }

    /**
     * @inheritDoc
     */
    public function getAll(?string $group = null): array
    {
        $adapterData = array_reverse(
            array_map(static fn (AdapterInterface $adapter) => $adapter->getAll($group), $this->priorityList)
        );

        return array_replace_recursive($adapterData[0], ...$adapterData);
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
