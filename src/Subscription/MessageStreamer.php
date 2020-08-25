<?php

namespace LeNats\Subscription;

use Exception;
use LeNats\Exceptions\ConnectionException;
use LeNats\Exceptions\StreamException;
use LeNats\Services\Connection;
use LeNats\Support\Protocol;
use LeNats\Support\RandomGenerator;
use LeNats\Support\Stream;

abstract class MessageStreamer
{
    /**
     * @var Connection
     */
    protected $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    /**
     * @param  string              $sid
     * @param  int|null            $quantity
     * @throws StreamException
     * @throws ConnectionException
     */
    public function unsubscribe(string $sid, ?int $quantity = null): void
    {
        $params = [$sid];

        if ($quantity !== null) {
            $params[] = $quantity;
        }

        $this->getStream()->write(Protocol::UNSUB, $params);
    }

    /**
     * @return Connection
     */
    public function getConnection(): Connection
    {
        return $this->connection;
    }

    /**
     * @throws ConnectionException
     * @return Stream
     */
    protected function getStream(): Stream
    {
        return $this->connection->getStream();
    }

    /**
     * @param string $inbox
     * @param string|null $sid
     * @return string
     * @throws ConnectionException
     * @throws StreamException
     */
    protected function createSubscriptionInbox(string $inbox, ?string $sid = null): string
    {
        if ($sid === null) {
            $sid = uniqid('sid');
        }

        $this->getStream()->write(Protocol::SUB, [$inbox, $sid]);

        return $sid;
    }
}
