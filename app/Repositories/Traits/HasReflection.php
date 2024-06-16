<?php

namespace App\Repositories\Traits;

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
        if ($attributes = $this->reflection->getMethod($name)->getAttributes()) {
            foreach ($attributes as $attribute) {
                if ($result = $attribute->newInstance()(static::class, $name, $arguments, fn () => $this->{$name}(...$arguments))) {
                    return $result;
                }
            }
        }

        return $this->{$name}(...$arguments);
    }
}
