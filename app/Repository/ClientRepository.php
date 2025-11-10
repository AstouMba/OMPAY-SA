<?php

namespace App\Repository;

use App\Models\Client;

class ClientRepository
{
    public function firstOrCreate(array $attributes)
    {
        return Client::firstOrCreate($attributes);
    }

    public function findByNci($nci)
    {
        return Client::where('nci', $nci)->first();
    }
}