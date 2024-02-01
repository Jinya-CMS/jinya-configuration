<?php

namespace Jinya\Configuration\Adapter;

use PHPUnit\Framework\TestCase;

class ArrayAdapterTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        $this->arrayAdapter = new ArrayAdapter(
            [
                'hello' => 'world',
                'foo' => [
                    'bar' => 'baz',
                ],
            ],
        );
    }

    private ArrayAdapter $arrayAdapter;

    public function testGetAll(): void
    {
        $allNoGroup = $this->arrayAdapter->getAll();
        self::assertCount(1, $allNoGroup);

        $allGroup = $this->arrayAdapter->getAll('foo');
        self::assertCount(1, $allGroup);

        $allInvalidGroup = $this->arrayAdapter->getAll('nonexisting');
        self::assertEmpty($allInvalidGroup);
    }

    public function testDelete(): void
    {
        $this->arrayAdapter->delete('hello');
        self::assertCount(1, $this->arrayAdapter->getConfig());

        $this->arrayAdapter->delete('foo');
        self::assertCount(1, $this->arrayAdapter->getConfig());
        self::assertArrayHasKey('foo', $this->arrayAdapter->getConfig());

        $this->arrayAdapter->delete('bar', 'foo');
        self::assertCount(1, $this->arrayAdapter->getConfig());
        self::assertArrayHasKey('foo', $this->arrayAdapter->getConfig());
        /** @phpstan-ignore-next-line */
        self::assertArrayNotHasKey('bar', $this->arrayAdapter->getConfig()['foo']);

        $this->arrayAdapter->delete('bar');
        self::assertArrayHasKey('foo', $this->arrayAdapter->getConfig());
        self::assertEmpty($this->arrayAdapter->getConfig()['foo']);
    }

    public function testSet(): void
    {
        $this->arrayAdapter->set('hello', 'hello');
        $this->arrayAdapter->set('hello', 'world', 'foo');
        $this->arrayAdapter->set('hello', 'world', 'bar');

        $data = $this->arrayAdapter->getConfig();
        self::assertEquals('hello', $data['hello']);
        /** @phpstan-ignore-next-line */
        self::assertEquals('world', $data['foo']['hello']);
        /** @phpstan-ignore-next-line */
        self::assertEquals('world', $data['bar']['hello']);
    }

    public function testGet(): void
    {
        $value = $this->arrayAdapter->get('hello');
        self::assertEquals('world', $value);

        $value = $this->arrayAdapter->get('bar', 'foo');
        self::assertEquals('baz', $value);
    }
}
