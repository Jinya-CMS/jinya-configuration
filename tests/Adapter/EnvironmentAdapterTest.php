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
        putenv('GETHELLO=world');
        $env = $this->environmentAdapter->get('gethello');

        self::assertEquals('world', $env);

        putenv('GETHELLO_WORLD=world');
        $env = $this->environmentAdapter->get('world', 'gethello');

        self::assertEquals('world', $env);

        $env = $this->environmentAdapter->get('getworld', default: 'hello');

        self::assertEquals('hello', $env);
    }

    public function testSet(): void
    {
        $this->environmentAdapter->set('hello', 'world', 'setworld');

        $setEnv = getenv('SETWORLD_HELLO');
        assertEquals('world', $setEnv);

        $this->environmentAdapter->set('setworld', 'world');

        $setEnv = getenv('SETWORLD');
        assertEquals('world', $setEnv);
    }

    public function testDelete(): void
    {
        putenv('DELETEGOODBYE=world');
        $this->environmentAdapter->delete('deletegoodbye');

        self::assertFalse(getenv('DELETEGOODBYE'));

        putenv('DELETEGOODBYE_WORLD=world');
        $this->environmentAdapter->delete('world', 'deletegoodbye');

        self::assertFalse(getenv('DELETEGOODBYE_WORLD'));
    }

    public function testGetAll(): void
    {
        $data = getenv();
        $env = $this->environmentAdapter->getAll();

        self::assertEquals($data, $env);

        $env = $this->environmentAdapter->getAll('php');
        $data = array_filter($data, static fn (string $key) => str_starts_with($key, 'PHP_'), ARRAY_FILTER_USE_KEY);

        self::assertEquals($data, $env);
    }
}
