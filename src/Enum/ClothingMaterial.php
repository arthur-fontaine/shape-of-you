<?php

namespace App\Enum;

enum ClothingMaterial: string
{
    case COTTON = 'cotton';
    case LINEN = 'linen';
    case HEMP = 'hemp';
    case WOOL = 'wool';
    case SILK = 'silk';
    case LEATHER = 'leather';
    case SUEDE = 'suede';
    case FUR = 'fur';
    
    case VISCOSE = 'viscose';
    case ACETATE = 'acetate';
    case CUPRO = 'cupro';
    
    case POLYESTER = 'polyester';
    case NYLON = 'nylon';
    case ACRYLIC = 'acrylic';
    case ELASTANE = 'elastane';
    case POLYURETHANE = 'polyurethane';
    
    case SOFTSHELL = 'softshell';
    case NEOPRENE = 'neoprene';
    case KEVLAR = 'kevlar';
    case COOLMAX = 'coolmax';
    case OUTLAST = 'outlast';
    case THERMOLITE = 'thermolite';
}
