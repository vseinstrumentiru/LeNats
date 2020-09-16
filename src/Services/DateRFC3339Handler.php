<?php
declare(strict_types=1);

namespace LeNats\Services;

use DateTime;
use \JMS\Serializer\Handler\SubscribingHandlerInterface;
use \JMS\Serializer\Handler\DateHandler;

/**
 * Decorates a DateHandler to bypass a bug with nano precision recognition
 * @see     https://bugs.php.net/bug.php?id=64814
 */
class DateRFC3339Handler implements SubscribingHandlerInterface
{
    /**
     * @var DateHandler
     */
    private $decoratedHandler;

    /**
     * @var string
     */
    private $defaultFormat;

    /**
     * @param string $defaultFormat
     * @param string $defaultTimezone
     * @param bool   $xmlCData
     */
    public function __construct(
        string $defaultFormat = DateTime::ATOM,
        string $defaultTimezone = 'UTC',
        bool $xmlCData = true
    ) {
        $this->decoratedHandler = new DateHandler($defaultFormat, $defaultTimezone, $xmlCData);
        $this->defaultFormat = $defaultFormat;
    }

    /**
     * @param $name
     * @param $arguments
     *
     * @return mixed
     * @throws \Exception
     */
    public function __call($name, array $arguments)
    {
        if (method_exists($this->decoratedHandler, $name)) {
            if ($name === 'serializeDateTimeImmutable' && is_string($arguments[1])) {
                $arguments[1] = new \DateTimeImmutable($arguments[1]);
            } else if (is_string($arguments[1])) {
                switch ($this->defaultFormat) {
                    case DateTime::ATOM:
                    case DateTime::RFC3339:
                    case DateTime::RFC3339_EXTENDED:
                    case DateTime::W3C:
                        $arguments[1] = $this->prepare($arguments[1]);
                        break;
                }
            }

            return call_user_func_array([$this->decoratedHandler, $name], $arguments);
        }

        return null;
    }

    /**
     * @inheritDoc
     */
    public static function getSubscribingMethods()
    {
        return DateHandler::getSubscribingMethods();
    }

    /**
     * Удаляет секцию *time-secfrac* из строки времени
     * @see https://bugs.php.net/bug.php?id=64814
     *
     * @param string $paramDatetime
     *
     * @return string
     *
     * @throws \Exception
     */
    private function prepare(string $paramDatetime): string
    {
        if (is_string($paramDatetime)) {
            preg_match(
                '#' .                           // open tag
                '(\d{4}-\d{2}-\d{2}T\d{2}:\d{2}:\d{2})' // date-time
                . '(\.\d+)?'                            // "Z" | time-numoffset
                . '((Z)|([+-].+))?'                     // time-offset
                . '#'                                   // close tag
                ,
                $paramDatetime,
                $matches
            );

            return $matches[1] . ($matches[5] ?? '+00:00');
        }

        throw new \LogicException('allowed only DateTimeInterface or string');
    }
}
