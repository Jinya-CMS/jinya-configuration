<?php

namespace Jinya\Configuration\Adapter;

use Jinya\Configuration\Adapter\Exceptions\DeleteNotSupportedException;
use Jinya\Configuration\Adapter\Exceptions\SetNotSupportedException;
use PHPUnit\Framework\TestCase;

class IniAdapterTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        $this->iniAdapter = new IniAdapter(__DIR__ . '/../files/sample.ini');
    }

    private IniAdapter $iniAdapter;

    public function testGetAll(): void
    {
        $allWithoutGroup = $this->iniAdapter->getAll();
        self::assertIsBool($allWithoutGroup['bool']);
        self::assertNull($allWithoutGroup['unset']);
        self::assertIsString($allWithoutGroup['string']);
        self::assertIsInt($allWithoutGroup['int']);

        self::assertFalse($allWithoutGroup['bool']);
        self::assertEquals('value', $allWithoutGroup['string']);
        self::assertEquals(0, $allWithoutGroup['int']);

        $allInFirstGroup = $this->iniAdapter->getAll('first_group');
        self::assertIsBool($allInFirstGroup['bool']);
        self::assertNull($allInFirstGroup['unset']);
        self::assertIsString($allInFirstGroup['string']);
        self::assertIsInt($allInFirstGroup['int']);

        self::assertTrue($allInFirstGroup['bool']);
        self::assertEquals('value1', $allInFirstGroup['string']);
        self::assertEquals(1, $allInFirstGroup['int']);

        $allInSecondGroup = $this->iniAdapter->getAll('second_group');
        self::assertIsBool($allInSecondGroup['bool']);
        self::assertNull($allInSecondGroup['unset']);
        self::assertIsString($allInSecondGroup['string']);
        self::assertIsInt($allInSecondGroup['int']);

        self::assertFalse($allInSecondGroup['bool']);
        self::assertEquals('value2', $allInSecondGroup['string']);
        self::assertEquals(2, $allInSecondGroup['int']);
    }

    public function testDelete(): void
    {
        $this->expectException(DeleteNotSupportedException::class);
        $this->iniAdapter->delete('test');
    }

    public function testGet(): void
    {
        self::assertNull($this->iniAdapter->get('unset'));
        self::assertFalse($this->iniAdapter->get('bool'));
        self::assertEquals('value', $this->iniAdapter->get('string'));
        self::assertEquals(0, $this->iniAdapter->get('int'));

        self::assertNull($this->iniAdapter->get('unset', 'first_group'));
        self::assertTrue($this->iniAdapter->get('bool', 'first_group'));
        self::assertEquals('value1', $this->iniAdapter->get('string', 'first_group'));
        self::assertEquals(1, $this->iniAdapter->get('int', 'first_group'));

        self::assertNull($this->iniAdapter->get('unset', 'second_group'));
        self::assertFalse($this->iniAdapter->get('bool', 'second_group'));
        self::assertEquals('value2', $this->iniAdapter->get('string', 'second_group'));
        self::assertEquals(2, $this->iniAdapter->get('int', 'second_group'));

        self::assertEquals('Hello World', $this->iniAdapter->get('non_existent', 'second_group', 'Hello World'));
    }

    public function testSet(): void
    {
        $this->expectException(SetNotSupportedException::class);
        $this->iniAdapter->set('test', 'Value');
    }
}
