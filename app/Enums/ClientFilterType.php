<?php

namespace App\Enums;

enum ClientFilterType: string
{
    case ALLOW = 'allow';
    case BLOCK = 'block';
}
