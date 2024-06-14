<?php

namespace App\Enums;

enum FilterItemPatternType: string
{
    case WILDCARD = 'wildcard';
    case REGEX = 'regex';
    case EXACT = 'exact';
    case DOMAIN = 'domain';
}
