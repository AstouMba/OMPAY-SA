<?php

namespace App\Repository;

use App\Models\Compte;

class CompteRepository
{
    public function getCompteByClientFirst()
    {
        return Compte::whereHas('client')->first();
    }

    public function getCompteById($id)
    {
        return Compte::find($id);
    }

    public function getComptesByClient($clientId)
    {
        return Compte::where('client_id', $clientId)->get();
    }

    public function create(array $attributes)
    {
        return Compte::create($attributes);
    }

    public function findByNumero($numero)
    {
        return Compte::where('numero_compte', $numero)->first();
    }
}