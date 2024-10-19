<?php
namespace App\Enum;


enum ProductStatus: string
{
    case DISPONIBLE = 'disponible';
    case EN_RUPTURE = 'en rupture';
    case PRECOMMANDE = 'precommande';
    case BIENTOT_DISPONIBLE = 'bientot disponible';

}

?>