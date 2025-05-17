<?php

namespace App\Enums;

enum DocumentType: string
{
    case NAISSANCE = 'naissance';
    case MARIAGE = 'mariage';
    case DECES = 'deces';

    public function label(): string
    {
        return match($this) {
            self::NAISSANCE => 'Acte de Naissance',
            self::MARIAGE => 'Acte de Mariage',
            self::DECES => 'Acte de Décès'
        };
    }
}
