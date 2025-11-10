<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ClientResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'nom' => $this->nom,
            'prenom' => $this->prenom,
            'telephone' => $this->telephone,
            'nci' => $this->nci,
            'date_creation' => $this->created_at,
            'date_modification' => $this->updated_at,
            'comptes' => CompteResource::collection($this->whenLoaded('comptes')),
        ];
    }
}
