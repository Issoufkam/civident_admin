<?php

namespace App\Enums;

// App\Enums\DocumentType.php
enum DocumentType: string
{
    case NAISSANCE = 'naissance';
    case MARIAGE = 'mariage';
    case DECES = 'deces';

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
