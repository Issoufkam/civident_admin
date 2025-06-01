<?php

namespace App\Enums;

enum DocumentStatus: string
{
    case EN_ATTENTE = 'en_attente';
    case APPROUVEE = 'approuvee';
    case REJETEE = 'rejetee';
    case VALIDATED = 'validated';

    public function color(): string
    {
        return match($this) {
            self::EN_ATTENTE => 'warning',
            self::APPROUVEE => 'success',
            self::REJETEE => 'danger',
            self::VALIDATED => 'primary'
        };
    }

    public function label(): string
    {
        return match($this) {
            self::EN_ATTENTE => 'En attente',
            self::APPROUVEE => 'Approuvée',
            self::REJETEE => 'Rejetée',
            self::VALIDATED => 'Validée'
        };
    }

    // Optionnel : méthode pour récupérer tous les cas
    public static function allCases(): array
    {
        return array_map(fn($case) => $case->value, self::cases());
    }
}
