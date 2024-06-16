<?php

namespace App\Base;

interface ReflectionAttribute
{
    public function __invoke(string $class, string $method, array $methodArguments, callable $methodCallable, ...$arguments): mixed;
}
