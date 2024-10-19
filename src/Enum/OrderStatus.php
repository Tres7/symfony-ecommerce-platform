<?php

namespace App\Enum;

enum OrderStatus: string
{
    case EN_PREPARATION = 'en préparation';
    case EXPEDIEE = 'expédiée';
    case LIVREE = 'livrée';
    case ANNULEE = 'annulée';

    /**
     * Obtenir tous les statuts disponibles sous forme de tableau.
     */
    public static function getAllStatuses(): array
    {
        return [
            self::EN_PREPARATION->value,
            self::EXPEDIEE->value,
            self::LIVREE->value,
            self::ANNULEE->value,
        ];
    }
}
