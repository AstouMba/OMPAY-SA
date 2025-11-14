<?php

namespace App\Http\Controllers;

use App\Models\Compte;
use App\Traits\ApiResponses;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SoldeController extends Controller
{
    use ApiResponses;

    /**
     * Get the balance of a specific account for the authenticated client
     */
    public function show(Request $request, $numero)
    {
        $client = Auth::user();

        if (!$client) {
            return $this->errorResponse('Client non authentifié', 401);
        }

        // Find the account by numero_compte and ensure it belongs to the authenticated client
        $compte = Compte::where('numero_compte', $numero)
            ->where('client_id', $client->id)
            ->first();

        if (!$compte) {
            return $this->errorResponse('Compte non trouvé ou accès non autorisé', 404);
        }

        // Check if account is active
        if ($compte->statut !== 'actif') {
            return $this->errorResponse('Compte inactif', 403);
        }

        $data = [
            'compte_id' => $compte->id,
            'numero_compte' => $compte->numero_compte,
            'solde' => $compte->solde,
            'devise' => $compte->devise,
        ];

        return $this->successResponse($data, 'Solde récupéré avec succès');
    }
}