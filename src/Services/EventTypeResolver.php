<?php

namespace LeNats\Services;

class EventTypeResolver
{
    /** @var string[] */
    private $types;

    public function __construct(array $types)
    {
        $this->types = $types;
    }

    public function getClass(string $eventType): ?string
    {
        $class = null;

        foreach ($this->types as $typeWildcard => $eventClass) {
            if ($typeWildcard === $eventType || $this->is($typeWildcard, $eventType)) {
                $class = $eventClass;
                break;
            }
        }

        return $class;
    }

    /**
     * Determine if a given string matches a given pattern.
     *
     * @param  string|array  $pattern
     * @param  string  $value
     * @return bool
     */
    public function is($pattern, $value)
    {
        if (empty($pattern)) {
            return false;
        }

        // If the given value is an exact match we can of course return true right
        // from the beginning. Otherwise, we will translate asterisks and do an
        // actual pattern match against the two strings to see if they match.
        if ($pattern == $value) {
            return true;
        }

        $pattern = preg_quote($pattern, '#');

        // Asterisks are translated into zero-or-more regular expression wildcards
        // to make it convenient to check if the strings starts with the given
        // pattern such as "library/*", making any string check convenient.
        $pattern = str_replace('\*', '.*', $pattern);

        if (preg_match('#^'.$pattern.'\z#u', $value) === 1) {
            return true;
        }

        return false;
    }
}
