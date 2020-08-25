<?php

declare(strict_types=1);

namespace LeNats\Tests\Services;

use LeNats\Services\Configuration;
use PHPUnit\Framework\TestCase;

class ConfigurationTest extends TestCase
{
    public function testConfigurationEmpty(): void
    {
        $cfg = new Configuration();
        $this->assertSame(PHP_VERSION, $cfg->getVersion());
        $this->assertNull($cfg->getPubPrefix());
        $this->assertNull($cfg->getSubRequests());
        $this->assertNull($cfg->getUnsubRequests());
        $this->assertNull($cfg->getSubCloseRequests());
        $this->assertNull($cfg->getCloseRequests());
        $this->assertNull($cfg->getClientId());
        $this->assertNull($cfg->getUser());
        $this->assertNull($cfg->getPass());
        $this->assertNull($cfg->getDsn());
    }
}
