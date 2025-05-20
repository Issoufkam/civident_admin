<?php

namespace App\Enums;

enum UserRole: string
{
    case ADMIN = 'admin';
    case AGENT = 'agent';
    case CITOYEN = 'citoyen';

    public function label(): string
    {
        return match($this) {
            self::ADMIN => 'Administrateur',
            self::AGENT => 'Agent de mairie',
            self::CITOYEN => 'Citoyen',
        };
    }

    public static function getRoles(): array
    {
        return array_column(self::cases(), 'value');
    }
}
