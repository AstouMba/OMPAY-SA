<?php

namespace App\Services;

use App\Models\Client;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class QrCodeService
{
    /**
     * Génère le QR Code pour un client
     *
     * @param Client $client
     * @return array
     * @throws \Exception
     */
    public function generateClientQrCode(Client $client): array
    {
        // Récupérer le premier compte non supprimé (pas de colonne statut)
        $compte = $client->comptes()->where('est_supprime', false)->first();

        if (!$compte) {
            throw new \Exception('Aucun compte actif trouvé pour ce client.');
        }

        $payload = [
            'numero_compte' => $compte->numero_compte,
            'nom_prenom' => $client->nom . ' ' . $client->prenom,
        ];

        $qr = QrCode::format('png')->size(300)->generate(json_encode($payload));
        $base64 = 'data:image/png;base64,' . base64_encode($qr);

        return [
            'numero_compte' => $compte->numero_compte,
            'qr_code_base64' => $base64,
        ];
    }
}