<?php

declare(strict_types=1);

namespace LeNats\Tests\Services;

use LeNats\Services\DateRFC3339Handler;
use PHPUnit\Framework\TestCase;
use ReflectionClass;

class DateRFC3339HandlerTest extends TestCase
{
    /**
     * @dataProvider datetimeProvider
     *
     * @param string|\DateTimeInterface $datetimeRfc3339
     * @param        $expected
     *
     * @throws \ReflectionException
     */
    public function testPrepare($datetimeRfc3339, $expected)
    {
        $handler = new DateRFC3339Handler();
        $reflection = new ReflectionClass($handler);
        $method = $reflection->getMethod('prepare');
        $method->setAccessible(true);
        $actual = $method->invoke($handler, $datetimeRfc3339);
        $this->assertEquals($expected, $actual);
    }

    public function datetimeProvider(): array
    {
        return [
            'RFC3339 formatted date  which php can parse'        => [
                '2020-06-08T12:46:41+00:00',
                new \DateTimeImmutable('2020-06-08T12:46:41+00:00'),
            ],
            'RFC3339 formatted date with numsec'                 => [
                '2020-06-08T14:50:22.257114',
                new \DateTimeImmutable('2020-06-08T14:50:22+00:00'),
            ],
            'RFC3339 formatted date with numsec and Z'           => [
                '2020-06-08T14:50:22.257114Z',
                new \DateTimeImmutable('2020-06-08T14:50:22+00:00'),
            ],
            'RFC3339 formatted date with numsec and time offset' => [
                '2006-12-12T10:01:02.999999999-04:00',
                new \DateTimeImmutable('2006-12-12T10:01:02-04:00'),
            ],
            'RFC3339 \DateTimeImmutable'                         => [
                new \DateTime('2006-12-12T10:01:02-04:00'),
                new \DateTimeImmutable('2006-12-12T10:01:02-04:00')
            ],
        ];
    }
}
