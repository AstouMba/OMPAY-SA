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
 *     path="/v1/transactions",
 *     summary="Lister toutes les transactions",
 *     description="Récupère la liste de toutes les transactions",
 *     operationId="getTransactions",
 *     tags={"Transactions"},
 *     @OA\Response(
 *         response=200,
 *         description="Liste des transactions récupérée",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="success", type="boolean", example=true),
 *             @OA\Property(property="message", type="string", example="liste des transactions recupérée avec succés"),
 *             @OA\Property(property="data", type="array", @OA\Items(ref="#/components/schemas/Transaction"))
 *         )
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