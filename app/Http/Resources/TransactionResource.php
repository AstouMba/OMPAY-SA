<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TransactionResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        // Déterminer le préfixe selon le type de transaction
        $prefix = '';
        if (in_array($this->type, ['retrait', 'transfert_debit', 'paiement_marchand'])) {
            $prefix = '-';
        } elseif (in_array($this->type, ['depot', 'transfert_credit'])) {
            $prefix = '+';
        }

        return [
            'id' => $this->id,
            'compte_id' => $this->compte_id,
            'marchand' => MarchandResource::make($this->whenLoaded('marchand')),
            'telephone_marchand' => $this->telephone_marchand,
            'type' => $this->type,
            'montant' => $prefix . $this->montant,
            'statut' => $this->statut,
            'date_creation' => $this->created_at,
            'date_modification' => $this->updated_at,
        ];
    }
}
