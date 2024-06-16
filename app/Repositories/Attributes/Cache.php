<?php

namespace App\Repositories\Attributes;

use App\Base\ReflectionAttribute;

#[\Attribute(\Attribute::TARGET_METHOD)]
class Cache implements ReflectionAttribute
{
    /**
     * @param \DateInterval|\DateTimeInterface|int|null $ttl
     */
    public function __construct(
        public readonly \DateInterval|\DateTimeInterface|int|null $ttl = 60,
    ) {
        //
    }

    public function __invoke(string $class, string $method, array $methodArguments, callable $methodCallable, ...$arguments): mixed
    {
        return $this->remember([$class], self::getKey($class, $method, $methodArguments), $methodCallable);
    }

    public static function getKey(string $class, string $method, array $argument): string
    {
        $key = $class.':'.$method;
        if (!empty($argument)) {
            $key .= ':'.substr(sha1(serialize($argument)), 0, 8);
        }

        return $key;
    }

    public function remember(array $tags, string $key, mixed $value)
    {
        return cache()->tags($tags)->remember($key, $this->ttl, $value);
    }
}
