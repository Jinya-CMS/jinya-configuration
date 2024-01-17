<?php

namespace Jinya\Configuration\Adapter;

use PHPUnit\Framework\TestCase;

use function PHPUnit\Framework\assertEquals;

class EnvironmentAdapterTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        $this->environmentAdapter = new EnvironmentAdapter();
    }

    private EnvironmentAdapter $environmentAdapter;

    public function testGet(): void
    {
        putenv('HELLO=world');
        $env = $this->environmentAdapter->get('hello');

        self::assertEquals('world', $env);

        putenv('HELLO_WORLD=world');
        $env = $this->environmentAdapter->get('world', 'hello');

        self::assertEquals('world', $env);

        $env = $this->environmentAdapter->get('world', default: 'hello');

        self::assertEquals('hello', $env);
    }

    public function testSet(): void
    {
        $this->environmentAdapter->set('hello', 'world', 'world');

        $setEnv = getenv('WORLD_HELLO');
        assertEquals('world', $setEnv);

        $this->environmentAdapter->set('world', 'world');

        $setEnv = getenv('WORLD');
        assertEquals('world', $setEnv);
    }

    public function testDelete(): void
    {
        putenv('GOODBYE=world');
        $this->environmentAdapter->delete('goodbye');

        self::assertFalse(getenv('GOODBYE'));

        putenv('GOODBYE_WORLD=world');
        $this->environmentAdapter->delete('world', 'goodbye');

        self::assertFalse(getenv('GOODBYE'));
    }

    public function testGetAll(): void
    {
        $data = getenv();
        $env = $this->environmentAdapter->getAll();

        self::assertEquals($data, $env);
    }
}
