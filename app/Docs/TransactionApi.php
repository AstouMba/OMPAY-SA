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
 *
 * @OA\Post(
 *     path="/client/transactions/payment",
 *     summary="Paiement marchand",
 *     description="Effectue un paiement chez un marchand",
 *     operationId="payment",
 *     tags={"Transactions"},
 *     security={{"bearerAuth": {}}},
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="code_marchand", type="string", example="M123456", description="Code du marchand"),
 *             @OA\Property(property="numero_marchand", type="string", example="771234567", description="Numéro de téléphone du marchand"),
 *             @OA\Property(property="montant", type="integer", example=5000, description="Montant du paiement")
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Paiement effectué",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="success", type="boolean", example=true),
 *             @OA\Property(property="message", type="string", example="Paiement effectué avec succès"),
 *             @OA\Property(property="data", type="object",
 *                 @OA\Property(property="transaction_id", type="string", example="550e8400-e29b-41d4-a716-446655440000"),
 *                 @OA\Property(property="type", type="string", example="paiement_marchand"),
 *                 @OA\Property(property="identifiant_marchand", type="string", example="M123456"),
 *                 @OA\Property(property="montant", type="string", example="-5000"),
 *                 @OA\Property(property="solde_apres", type="integer", example=95000),
 *                 @OA\Property(property="date_creation", type="string", format="date-time", example="2025-11-09T10:05:00Z")
 *             )
 *         )
 *     ),
 *     @OA\Response(
 *         response=400,
 *         description="Erreur de validation",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="success", type="boolean", example=false),
 *             @OA\Property(property="message", type="string", example="Solde insuffisant")
 *         )
 *     ),
 *     @OA\Response(
 *         response=401,
 *         description="Non authentifié",
 *         @OA\JsonContent(ref="#/components/schemas/ErrorResponse")
 *     ),
 *     @OA\Response(
 *         response=500,
 *         description="Erreur serveur",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="success", type="boolean", example=false),
 *             @OA\Property(property="message", type="string", example="Erreur lors du paiement")
 *         )
 *     )
 * )
 *
 * @OA\Post(
 *     path="/client/transactions/transfert",
 *     summary="Transfert entre clients OMPay",
 *     description="Effectue un transfert d'argent vers un autre client OMPay",
 *     operationId="transfert",
 *     tags={"Transactions"},
 *     security={{"bearerAuth": {}}},
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="numero_ompay", type="string", example="771234568", description="Numéro OMPay du destinataire"),
 *             @OA\Property(property="montant", type="integer", example=3000, description="Montant du transfert")
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Transfert effectué avec succès",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="success", type="boolean", example=true),
 *             @OA\Property(property="message", type="string", example="Transfert effectué avec succès"),
 *             @OA\Property(property="data", type="object",
 *                 @OA\Property(property="transaction_id", type="string", example="550e8400-e29b-41d4-a716-446655440001"),
 *                 @OA\Property(property="type", type="string", example="transfert"),
 *                 @OA\Property(property="montant", type="integer", example=3000),
 *                 @OA\Property(property="frais", type="integer", example=24),
 *                 @OA\Property(property="emetteur", type="object",
 *                     @OA\Property(property="numero_ompay", type="string", example="771234567"),
 *                     @OA\Property(property="solde_apres", type="integer", example=92024)
 *                 ),
 *                 @OA\Property(property="destinataire", type="object",
 *                     @OA\Property(property="numero_ompay", type="string", example="771234568"),
 *                     @OA\Property(property="nom_prenom", type="string", example="Moussa Diallo"),
 *                     @OA\Property(property="montant_recu", type="integer", example=3000)
 *                 ),
 *                 @OA\Property(property="date_creation", type="string", format="date-time", example="2025-11-09T10:05:00Z")
 *             )
 *         )
 *     ),
 *     @OA\Response(
 *         response=400,
 *         description="Erreur de validation",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="success", type="boolean", example=false),
 *             @OA\Property(property="message", type="string", example="Solde insuffisant")
 *         )
 *     ),
 *     @OA\Response(
 *         response=401,
 *         description="Non authentifié",
 *         @OA\JsonContent(ref="#/components/schemas/ErrorResponse")
 *     ),
 *     @OA\Response(
 *         response=500,
 *         description="Erreur serveur",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="success", type="boolean", example=false),
 *             @OA\Property(property="message", type="string", example="Erreur lors du transfert")
 *         )
 *     )
 * )
 */
class TransactionApi {}