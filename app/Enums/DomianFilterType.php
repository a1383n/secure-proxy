<?php

namespace App\Enums;

enum DomianFilterType: string
{
    case ALLOW = 'allow';
    case BLOCK = 'block';
    case BYPASS = 'bypass';
}
