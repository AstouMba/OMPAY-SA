<?php

namespace App\Docs;

use OpenApi\Annotations as OA;

/**
 * @OA\Tag(
 *     name="Client QR Code",
 *     description="Opérations liées au QR Code du client"
 * )
 *
 * @OA\Get(
 *     path="/client/qrcode",
 *     summary="Récupérer le QR Code du client",
 *     description="Permet à un client connecté de récupérer le QR Code lié à son compte. Le QR Code contient les informations du numéro de compte et du nom/prénom du client.",
 *     operationId="getClientQrCode",
 *     tags={"Client QR Code"},
 *     security={{"bearerAuth": {}}},
 *     @OA\Response(
 *         response=200,
 *         description="QR Code généré avec succès",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="success", type="boolean", example=true),
 *             @OA\Property(property="data", ref="#/components/schemas/ClientQrResponse")
 *         )
 *     ),
 *     @OA\Response(
 *         response=401,
 *         description="Non authentifié",
 *         @OA\JsonContent(ref="#/components/schemas/ErrorResponse")
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="Aucun compte actif trouvé",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="success", type="boolean", example=false),
 *             @OA\Property(property="message", type="string", example="Aucun compte actif trouvé pour ce client.")
 *         )
 *     ),
 *     @OA\Response(
 *         response=500,
 *         description="Erreur lors de la génération du QR Code",
 *         @OA\JsonContent(ref="#/components/schemas/ErrorResponse")
 *     )
 * )
 */
class ClientQrApi {}