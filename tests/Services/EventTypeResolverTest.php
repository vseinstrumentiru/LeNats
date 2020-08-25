<?php

declare(strict_types=1);

namespace LeNats\Tests\Services;

use LeNats\Services\EventTypeResolver;
use PHPUnit\Framework\TestCase;

class EventTypeResolverTest extends TestCase
{
    public function testGetClass(): void
    {
        $resolver = new EventTypeResolver(['foo' => __CLASS__]);
        $this->assertNull($resolver->getClass('test'));
        $this->assertSame(__CLASS__, $resolver->getClass('foo'));
    }
}
