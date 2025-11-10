<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CompteResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'numero_compte' => $this->numero_compte,
            'client' => ClientResource::make($this->whenLoaded('client')),
            'type_compte' => $this->type_compte,
            'devise' => $this->devise,
            'solde' => $this->solde(),
            'est_supprime' => $this->est_supprime,
            'date_creation' => $this->created_at,
            'date_modification' => $this->updated_at,
            'transactions' => TransactionResource::collection($this->whenLoaded('transactions')),
        ];
    }
}
