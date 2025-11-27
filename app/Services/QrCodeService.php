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
            'client_name' => $client->prenom, // Utiliser seulement le prénom
            'type' => 'ompay_account'
        ];

        // Génération QR Code en format PNG base64 pour réduire la taille
        $qrData = QrCode::format('png')
            ->size(300)
            ->margin(2)
            ->encoding('UTF-8')
            ->generate(json_encode($payload));

        // Convertir en base64 pour envoyer à Flutter
        $qrBase64 = base64_encode($qrData);

        return [
            'numero_compte'  => $compte->numero_compte,
            'qr_code_base64' => $qrBase64, // Base64 PNG pour Flutter
            'qr_data' => json_encode($payload) // Données brutes au cas où Flutter veut générer son propre QR
        ];
    }
}
