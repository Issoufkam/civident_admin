<?php

namespace App\Enums;

enum DocumentStatus: string
{
    case EN_ATTENTE = 'en_attente';
    case APPROUVEE = 'approuvee';
    case REJETEE = 'rejetee';

    public function color(): string
    {
        return match($this) {
            self::EN_ATTENTE => 'orange',
            self::APPROUVEE => 'green',
            self::REJETEE => 'red'
        };
    }
}
