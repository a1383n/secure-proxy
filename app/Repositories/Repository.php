<?php

namespace App\Repositories;

use App\Base\BaseRepository;
use App\Repositories\Traits\Bootable;
use App\Repositories\Traits\HasReflection;

abstract class Repository extends BaseRepository
{
    use Bootable, HasReflection;

    public function __construct()
    {
        $this->boot();
    }
}
