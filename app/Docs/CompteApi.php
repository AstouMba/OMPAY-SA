<?php

namespace App\Docs;

use OpenApi\Annotations as OA;

/**
 * @OA\Tag(
 *     name="Comptes",
 *     description="Opérations liées aux comptes"
 * )
 *
 * @OA\PathItem(
 *     path="/solde"
 * )
 *
 * @OA\Get(
 *     path="/solde",
 *     summary="Obtenir le solde du compte",
 *     description="Récupère le solde actuel du compte du client connecté",
 *     operationId="getSolde",
 *     tags={"Comptes"},
 *     @OA\Response(
 *         response=200,
 *         description="Solde récupéré avec succès",
 *         @OA\JsonContent(ref="#/components/schemas/SoldeResponse")
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="Aucun compte trouvé",
 *         @OA\JsonContent(ref="#/components/schemas/ErrorResponse")
 *     ),
 *     @OA\Response(
 *         response=500,
 *         description="Erreur serveur",
 *         @OA\JsonContent(ref="#/components/schemas/ErrorResponse")
 *     )
 * )
 */
class CompteApi {}
