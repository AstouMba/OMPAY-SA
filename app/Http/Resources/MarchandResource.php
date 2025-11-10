<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MarchandResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'nom' => $this->nom,
            'code_marchand' => $this->code_marchand,
            'telephone' => $this->telephone,
            'date_creation' => $this->created_at,
            'date_modification' => $this->updated_at,
        ];
    }
}