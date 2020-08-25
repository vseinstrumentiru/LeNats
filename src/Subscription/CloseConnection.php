<?php

namespace LeNats\Subscription;

use Google\Protobuf\Internal\Message;
use LeNats\Listeners\Responses\CloseConnectionResponseListener;
use NatsStreamingProtocol\CloseRequest;

class CloseConnection extends SubscriptionMessageStreamer
{
    protected function getRequest(Subscription $subscription): Message
    {
        $request = new CloseRequest();
        $config = $this->connection->getConfig();
        $request->setClientID($config->getClientId());

        return $request;
    }

    protected function getPublishSubject(Subscription $subscription): ?string
    {
        $subject = $this->connection->getConfig()->getCloseRequests();
        if (empty($subject)) {
            throw new \LogicException('subject is empty');
        }

        return $this->connection->getConfig()->getCloseRequests();
    }

    protected function getMessageListenerClass(): ?string
    {
        return null;
    }

    protected function getResponseListenerClass(): string
    {
        return CloseConnectionResponseListener::class;
    }
}
