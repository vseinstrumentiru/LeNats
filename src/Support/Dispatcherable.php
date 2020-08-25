<?php

namespace LeNats\Support;

use Symfony\Component\EventDispatcher\Event;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\EventDispatcher\LegacyEventDispatcherProxy;

trait Dispatcherable
{
    /**
     * @var EventDispatcherInterface
     */
    protected $dispatcher;

    /**
     * @param EventDispatcherInterface $dispatcher
     */
    public function setDispatcher(EventDispatcherInterface $dispatcher): void
    {
        $this->dispatcher = LegacyEventDispatcherProxy::decorate($dispatcher);
    }

    /**
     * @param Event       $event
     * @param string|null $eventName
     */
    public function dispatch(Event $event, ?string $eventName = null): void
    {
        $this->dispatcher->dispatch($event, $eventName);
    }
}
