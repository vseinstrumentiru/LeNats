<?php

namespace LeNats\Tests;

use LeNats\Events\React\Data;
use LeNats\Services\Connection;

class ConnectionTest extends TestCase
{
    /** @test */
    public function itCreatesConnection(): void
    {
        $this->assertTrue($this->getStream()->isConnected());
    }

    /** @test */
    public function itReceivesData(): void
    {
        $dispatcher = $this->getContainer()->get('event_dispatcher');

        $received = false;

        $dispatcher->addListener(Data::class, static function () use (&$received) {
            $received = true;
        });

        fwrite($this->resource, 'Test data');
        rewind($this->resource);

        $connection = $this->getContainer()->get(Connection::class);
        $connection->setLoop($this->loop);
        $stream = $this->getStream();
        $connection->setStream($stream);
        $connection->configureStream($stream, [Data::class]);

        $connection->runTimer('test', 1);

        $this->assertTrue($received);
    }

    /** @test */
    public function itHandlesEvents(): void
    {
        $stream = $this->getStream();

        $onEnd = false;
        $stream->on('end', static function () use (&$onEnd) {
            $onEnd = true;
        });

        $stream->emit('end');

        $onClose = false;
        $stream->on('close', static function () use (&$onClose) {
            $onClose = true;
        });

        unset($stream);

        $this->assertTrue($onClose);
        $this->assertTrue($onEnd);
    }
}
