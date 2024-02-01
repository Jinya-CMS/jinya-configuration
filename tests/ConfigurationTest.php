<?php

namespace Jinya\Configuration;

use Jinya\Configuration\Adapter\ArrayAdapter;
use Jinya\Configuration\Adapter\EnvironmentAdapter;
use Jinya\Configuration\Adapter\Exceptions\DeleteNotSupportedException;
use Jinya\Configuration\Adapter\Exceptions\SetNotSupportedException;
use Jinya\Configuration\Adapter\IniAdapter;
use PHPUnit\Framework\TestCase;

class ConfigurationTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        $this->iniAdapter = new IniAdapter(__DIR__ . '/files/sample.ini');
        $this->environmentAdapter = new EnvironmentAdapter();
        $this->arrayAdapter = new ArrayAdapter(
            [
                'hello' => 'world',
                'foo' => [
                    'bar' => 'baz',
                ],
                'second_group' => [
                    'int' => 5,
                ],
            ],
        );
        $this->configuration = new Configuration([$this->arrayAdapter, $this->iniAdapter, $this->environmentAdapter]);
        putenv('WORLD=hello');
    }

    private IniAdapter $iniAdapter;
    private EnvironmentAdapter $environmentAdapter;
    private ArrayAdapter $arrayAdapter;
    private Configuration $configuration;

    public function testAddAdapter(): void
    {
        $configuration = new Configuration();
        $configuration->addAdapter($this->arrayAdapter);
        self::assertEquals($this->arrayAdapter->get('hello'), $configuration->get('hello'));

        $configuration->addAdapter($this->iniAdapter, 0);
        self::assertEquals($this->arrayAdapter->get('hello'), $configuration->get('hello'));
        self::assertNotEquals(
            $this->arrayAdapter->get('int', 'second_group'),
            $configuration->get('int', 'second_group')
        );
        self::assertEquals(
            $this->iniAdapter->get('int', 'second_group'),
            $configuration->get('int', 'second_group')
        );
        self::assertNull($configuration->get('world'));
    }

    public function testGetAll(): void
    {
        // We need to factor all env vars in since they are not really filtered out by design
        $envCount = count($this->environmentAdapter->getAll());
        $noGroup = $this->configuration->getAll();
        self::assertEquals($this->arrayAdapter->get('hello'), $noGroup['hello']);
        self::assertEquals($this->environmentAdapter->get('world'), $noGroup['WORLD']);
        self::assertEquals($this->iniAdapter->get('bool'), $noGroup['bool']);
        self::assertEquals($this->iniAdapter->get('unset'), $noGroup['unset']);
        self::assertEquals($this->iniAdapter->get('string'), $noGroup['string']);
        self::assertEquals($this->iniAdapter->get('int'), $noGroup['int']);
        self::assertCount(5 + $envCount, $noGroup);
    }

    public function testDelete(): void
    {
        $this->expectException(DeleteNotSupportedException::class);
        $this->configuration->delete('key');
    }

    public function testGet(): void
    {
        self::assertEquals($this->arrayAdapter->get('hello'), $this->configuration->get('hello'));
        self::assertEquals(
            $this->arrayAdapter->get('int', 'second_group'),
            $this->configuration->get('int', 'second_group')
        );
        self::assertEquals(
            $this->iniAdapter->get('int', 'first_group'),
            $this->configuration->get('int', 'first_group')
        );
        self::assertEquals($this->environmentAdapter->get('world'), $this->configuration->get('world'));
    }

    public function testSet(): void
    {
        $this->expectException(SetNotSupportedException::class);
        $this->configuration->set('test', 'Value');
    }
}
