<?php

declare(strict_types=1);

namespace LeNats\Tests\Services;

use LeNats\Services\Configuration;
use NatsStreamingProtocol\ConnectResponse;
use PHPUnit\Framework\TestCase;

class ConfigurationTest extends TestCase
{
    public function testConfigurationEmpty(): void
    {
        $cfg = new Configuration();
        $this->assertSame(PHP_VERSION, $cfg->getVersion());
        $this->assertSame('php', $cfg->getLang());
        $this->assertNull($cfg->getPubPrefix());
        $this->assertNull($cfg->getSubRequests());
        $this->assertNull($cfg->getUnsubRequests());
        $this->assertNull($cfg->getSubCloseRequests());
        $this->assertNull($cfg->getCloseRequests());
        $this->assertNull($cfg->getClientId());
        $this->assertSame([], $cfg->getContext());
        $ctx = ['foo' => 'bar'];
        $cfg->setContext($ctx);
        $this->assertSame($ctx, $cfg->getContext());
        $cfg->setClientId('test');
        $this->assertSame('test', $cfg->getClientId());
        $this->assertNull($cfg->getUser());
        $this->assertNull($cfg->getPass());
        $this->assertNull($cfg->getDsn());

        $resp = new ConnectResponse();
        $resp->setPubPrefix('test');
        $resp->setSubRequests('test');
        $resp->setUnsubRequests('test');
        $resp->setSubCloseRequests('test');
        $resp->setCloseRequests('test');
        $cfg->configureConnection($resp);
        $this->assertSame('test', $cfg->getPubPrefix());
        $this->assertSame('test', $cfg->getSubRequests());
        $this->assertSame('test', $cfg->getUnsubRequests());
        $this->assertSame('test', $cfg->getSubCloseRequests());
        $this->assertSame('test', $cfg->getCloseRequests());
    }
}
