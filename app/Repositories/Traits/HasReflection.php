<?php

namespace App\Repositories\Traits;

use App\Repositories\Attributes\Cache;

trait HasReflection
{
    protected ?\ReflectionClass $reflection;

    protected function bootHasReflection(): void
    {
        $this->initializeReflection();
    }

    protected function initializeReflection(): void
    {
        $this->reflection ??= new \ReflectionClass($this);
    }

    public function __call(string $name, array $arguments)
    {
        if (($attributes = $this->reflection->getMethod($name)->getAttributes())) {
            foreach ($attributes as $attribute) {
                $result = $attribute
                    ->newInstance()
                    ->__invoke(static::class, $name, $arguments, fn() => $this->{$name}(...$arguments));

                if ($result !== null) {
                    return $result;
                }
            }
        }

        return $this->{$name}(...$arguments);
    }
}
