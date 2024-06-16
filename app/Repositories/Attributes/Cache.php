<?php

namespace App\Repositories\Attributes;

use App\Base\ReflectionAttribute;

#[\Attribute(\Attribute::TARGET_METHOD)]
class Cache implements ReflectionAttribute
{
    /**
     * @param \DateInterval|\DateTimeInterface|int|null $ttl
     * @param array|string[]|null $cachePrioritizeDrivers
     */
    public function __construct(
        public readonly \DateInterval|\DateTimeInterface|int|null $ttl = null,
        public ?array $cachePrioritizeDrivers = []
    )
    {
        //
    }

    public function __invoke(string $class, string $method, array $methodArguments, callable $methodCallable, ...$arguments): mixed
    {
        return $this->remember(self::getKey($class, $method, $methodArguments), $methodCallable);
    }

    public static function getKey(string $class, string $method, array $argument): string
    {
        $key = $class . ':' . $method;
        if (!empty($argument)) {
            $key .= ':' . substr(sha1(serialize($argument)), 0, 8);
        }

        return $key;
    }

    public function remember(string $key, mixed $value)
    {
        $cache = app('cache');
        $cachePrioritizeDrivers = collect($this->cachePrioritizeDrivers)
            ->push($cache->getDefaultDriver());

        foreach ($cachePrioritizeDrivers as $driver) {
            if ($result = $cache->driver($driver)->get($key)) {
                return $result;
            }
        }

        $result = value($value);

        foreach ($cachePrioritizeDrivers->reverse() as $driver) {
            throw_if(! $cache->driver($driver)->put($key, $result), 'RuntimeException');
        }

        return $result;
    }
}
