<?php

namespace App\Enum;

enum ClothingFit: string
{
    case LOOSE = 'ample';
    case SKINNY = 'skinny';
    case REGULAR = 'regular';
    case TAILORED = 'tailored';
    case FITTED = 'fitted';
    case OVERSIZED = 'oversized';
    case SLIM = 'slim';
    case RELAXED = 'relaxed';
    case TAPERED = 'tapered';
}
