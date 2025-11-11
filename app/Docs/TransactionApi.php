<?php

namespace App\Docs;

use OpenApi\Annotations as OA;

/**
 * @OA\Tag(
 *     name="Transactions",
 *     description="Opérations liées aux transactions"
 * )
 *
 * @OA\Get(
 *     path="/client/transactions",
 *     summary="Historique des transactions",
 *     description="Récupère l'historique des transactions du client connecté",
 *     operationId="getTransactions",
 *     tags={"Transactions"},
 *     security={{"bearerAuth": {}}},
 *     @OA\Parameter(
 *         name="page",
 *         in="query",
 *         description="Numéro de page",
 *         required=false,
 *         @OA\Schema(type="integer", default=1)
 *     ),
 *     @OA\Parameter(
 *         name="per_page",
 *         in="query",
 *         description="Nombre d'éléments par page",
 *         required=false,
 *         @OA\Schema(type="integer", default=15)
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Transactions récupérées",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="success", type="boolean", example=true),
 *             @OA\Property(property="message", type="string", example="liste des transactions recupérée avec succés"),
 *             @OA\Property(property="data", type="array", @OA\Items(ref="#/components/schemas/Transaction"))
 *         )
 *     ),
 *     @OA\Response(
 *         response=401,
 *         description="Non authentifié",
 *         @OA\JsonContent(ref="#/components/schemas/ErrorResponse")
 *     ),
 *     @OA\Response(
 *         response=500,
 *         description="Erreur lors de la récupération",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="success", type="boolean", example=false),
 *             @OA\Property(property="message", type="string", example="erreur lors de la recupération des transactions")
 *         )
 *     )
 * )
 */
class TransactionApi {}