<?php

namespace App\Base;

use Illuminate\Foundation\Application;

abstract class BaseRepository
{
    public static string $bindMethod = 'singleton';

    public static function register(Application $app): void
    {
        $app->{static::$bindMethod}(static::class, static::getInstance());
    }

    protected static function getInstance(): callable|string|static
    {
        return static::class;
    }
}
