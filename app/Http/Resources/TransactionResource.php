<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TransactionResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'compte_id' => $this->compte_id,
            'marchand' => MarchandResource::make($this->whenLoaded('marchand')),
            'telephone_marchand' => $this->telephone_marchand,
            'type' => $this->type,
            'montant' => $this->montant,
            'statut' => $this->statut,
            'date_creation' => $this->created_at,
            'date_modification' => $this->updated_at,
        ];
    }
}
