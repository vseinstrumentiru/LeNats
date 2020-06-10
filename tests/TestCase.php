<?php

namespace LeNats\Tests;

use LeNats\Support\Stream;
use React\EventLoop\Factory;
use React\EventLoop\LoopInterface;
use React\Socket\Connection as ReactConnection;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

abstract class TestCase extends KernelTestCase
{
    /** @var LoopInterface */
    protected $loop;

    /**
     * @var bool|resource
     */
    protected $resource;

    /**
     * @inheritDoc
     */
    public function setUp(): void
    {
        static::bootKernel();

        $this->loop = Factory::create();

        $this->resource = fopen('php://temp', 'w+');
    }

    /**
     * @return \Symfony\Component\DependencyInjection\ContainerInterface
     */
    protected function getContainer(): \Symfony\Component\DependencyInjection\ContainerInterface
    {
        if (static::$container !== null) {
            return static::$container;
        }

        return static::$kernel->getContainer();
    }

    /**
     * @return Stream
     */
    protected function getStream(): Stream
    {
        $stream = new ReactConnection($this->resource, $this->loop);

        return new Stream($stream, $this->loop);
    }

    /**
     * @param string $event
     * @param callable $emitter
     */
    protected function assertEventHandled(string $event, callable $emitter): void
    {
        $handled = false;

        $this->getContainer()->get('event_dispatcher')->addListener($event, static function () use (&$handled) {
           $handled = true;
        });

        $emitter();

        $this->assertTrue($handled);
    }
}
