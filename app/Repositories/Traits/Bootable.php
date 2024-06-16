<?php

namespace App\Repositories\Traits;

use Illuminate\Support\Str;

trait Bootable
{
    protected function boot(): void
    {
        collect(class_uses_recursive(static::class))
            ->reverse()
            ->except([__TRAIT__])
            ->values()
            ->each(function ($class) {
                try {
                    $this->{'boot'.Str::camel(class_basename($class))}();
                } catch (\BadMethodCallException) {
                    //
                }
            });
    }
}
