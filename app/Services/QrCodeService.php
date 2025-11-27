<?php

namespace App\Services;

use App\Models\Client;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class QrCodeService
{
    public function generateClientQrCode(Client $client): array
    {
        $compte = $client->comptes()->where('est_supprime', false)->first();

        if (!$compte) {
            throw new \Exception('Aucun compte actif trouvé pour ce client.');
        }

        $payload = [
            'numero_compte' => $compte->numero_compte,
            'nom_prenom'    => $client->nom . ' ' . $client->prenom,
        ];

        // Génération QR Code en SVG (pas d'extension requise)
        $svg = QrCode::format('svg')
            ->size(300)
            ->margin(2)
            ->encoding('UTF-8')
            ->generate(json_encode($payload));

        // Convertir SVG en base64 pour Flutter
        return [
            'numero_compte'  => $compte->numero_compte,
            'qr_code_base64' => 'data:image/svg+xml;base64,' . base64_encode($svg),
        ];
    }
}
