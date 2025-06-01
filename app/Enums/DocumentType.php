<?php

namespace App\Enums;

enum DocumentType: string
{
    case NAISSANCE = 'naissance';
    case MARIAGE = 'mariage';
    case DECES = 'deces';
}
