<?php

namespace App\Enums;

enum FilterMode: string
{
    case ALLOW = 'allow';
    case BLOCK = 'block';
}
