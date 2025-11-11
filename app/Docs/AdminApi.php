<?php
namespace App\Docs;
use OpenApi\Annotations as OA;

/**
 * @OA\Tag(
 *     name="Administration",
 *     description="Opérations d'administration et d'authentification admin"
 * )
 *
 * @OA\Post(
 *     path="/admin/login",
 *     summary="Connexion administrateur",
 *     description="Authentification d'un administrateur avec email et mot de passe",
 *     operationId="adminLogin",
 *     tags={"Administration"},
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"email", "password"},
 *             @OA\Property(property="email", type="string", format="email", example="admin@example.com", description="Email de l'administrateur"),
 *             @OA\Property(property="password", type="string", format="password", example="secret123", description="Mot de passe")
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Connexion réussie",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="success", type="boolean", example=true),
 *             @OA\Property(property="message", type="string", example="Connexion réussie"),
 *             @OA\Property(property="data", type="object",
 *                 @OA\Property(property="user", ref="#/components/schemas/User"),
 *                 @OA\Property(property="access_token", type="string", description="Token d'accès"),
 *                 @OA\Property(property="refresh_token", type="string", description="Token de rafraîchissement (id du token)") ,
 *                 @OA\Property(property="token_type", type="string", example="Bearer")
 *             )
 *         )
 *     ),
 *     @OA\Response(
 *         response=401,
 *         description="Identifiants invalides",
 *         @OA\JsonContent(ref="#/components/schemas/ErrorResponse")
 *     ),
 *     @OA\Response(
 *         response=403,
 *         description="Accès interdit",
 *         @OA\JsonContent(ref="#/components/schemas/ErrorResponse")
 *     )
 * )
 *
 * @OA\Post(
 *     path="/admin/logout",
 *     summary="Déconnexion administrateur",
 *     description="Déconnexion de l'administrateur connecté",
 *     operationId="adminLogout",
 *     tags={"Administration"},
 *     security={{"bearerAuth": {}}},
 *     @OA\Response(
 *         response=200,
 *         description="Déconnexion réussie",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="success", type="boolean", example=true),
 *             @OA\Property(property="message", type="string", example="Déconnexion réussie")
 *         )
 *     ),
 *     @OA\Response(
 *         response=401,
 *         description="Token invalide",
 *         @OA\JsonContent(ref="#/components/schemas/ErrorResponse")
 *     )
 * )
 *
 *
 * @OA\Post(
 *     path="/admin/clients",
 *     summary="Créer un client et son compte",
 *     description="Crée un nouveau client avec son compte associé. Remarque : l'admin doit être authentifié (access_token) et posséder un refresh_token obtenu via /v1/admin/login avant d'appeler cette route.",
 *     operationId="createClient",
 *     tags={"Administration"},
 *     security={{"bearerAuth": {}}},
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(ref="#/components/schemas/CreateClientRequest")
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Client et compte créés avec succès",
 *         @OA\JsonContent(ref="#/components/schemas/CreateClientResponse")
 *     ),
 *     @OA\Response(
 *         response=401,
 *         description="Non authentifié",
 *         @OA\JsonContent(ref="#/components/schemas/ErrorResponse")
 *     ),
 *     @OA\Response(
 *         response=422,
 *         description="Erreur de validation ou données invalides",
 *         @OA\JsonContent(ref="#/components/schemas/ErrorResponse")
 *     )
 * )
 *
 * @OA\Get(
 *     path="/admin/transaction-fees",
 *     summary="Lister les configurations de frais",
 *     description="Récupère toutes les configurations de frais de transaction",
 *     operationId="getTransactionFees",
 *     tags={"Administration"},
 *     security={{"bearerAuth": {}}},
 *     @OA\Response(
 *         response=200,
 *         description="Configurations récupérées",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="success", type="boolean", example=true),
 *             @OA\Property(property="message", type="string", example="Configurations de frais récupérées avec succès"),
 *             @OA\Property(property="data", type="array", @OA\Items(
 *                 type="object",
 *                 @OA\Property(property="id", type="integer", example=1),
 *                 @OA\Property(property="type_transaction", type="string", example="transfert"),
 *                 @OA\Property(property="pourcentage_frais", type="number", format="float", example=0.008),
 *                 @OA\Property(property="frais_fixe", type="number", format="float", example=0),
 *                 @OA\Property(property="actif", type="boolean", example=true)
 *             ))
 *         )
 *     ),
 *     @OA\Response(
 *         response=401,
 *         description="Non authentifié",
 *         @OA\JsonContent(ref="#/components/schemas/ErrorResponse")
 *     )
 * )
 *
 * @OA\Put(
 *     path="/admin/transaction-fees/{type}",
 *     summary="Modifier les frais d'un type de transaction",
 *     description="Met à jour la configuration des frais pour un type de transaction spécifique",
 *     operationId="updateTransactionFee",
 *     tags={"Administration"},
 *     security={{"bearerAuth": {}}},
 *     @OA\Parameter(
 *         name="type",
 *         in="path",
 *         required=true,
 *         description="Type de transaction (transfert, paiement_marchand, retrait, depot)",
 *         @OA\Schema(type="string", example="transfert")
 *     ),
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="pourcentage_frais", type="number", format="float", example=0.01, description="Pourcentage des frais (0.01 = 1%)"),
 *             @OA\Property(property="frais_fixe", type="number", format="float", example=100, description="Frais fixe en FCFA"),
 *             @OA\Property(property="actif", type="boolean", example=true, description="Activer/désactiver ces frais")
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Configuration mise à jour",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="success", type="boolean", example=true),
 *             @OA\Property(property="message", type="string", example="Configuration de frais mise à jour avec succès"),
 *             @OA\Property(property="data", type="object",
 *                 @OA\Property(property="id", type="integer", example=1),
 *                 @OA\Property(property="type_transaction", type="string", example="transfert"),
 *                 @OA\Property(property="pourcentage_frais", type="number", format="float", example=0.01),
 *                 @OA\Property(property="frais_fixe", type="number", format="float", example=100),
 *                 @OA\Property(property="actif", type="boolean", example=true)
 *             )
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
 *             @OA\Property(property="message", type="string", example="Erreur lors de la mise à jour")
 *         )
 *     )
 * )
 *
 * @OA\Post(
 *     path="/admin/transaction-fees/calculate",
 *     summary="Calculer les frais",
 *     description="Calcule les frais pour un montant et un type de transaction donnés",
 *     operationId="calculateTransactionFees",
 *     tags={"Administration"},
 *     security={{"bearerAuth": {}}},
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="montant", type="integer", example=3000, description="Montant de la transaction"),
 *             @OA\Property(property="type_transaction", type="string", example="transfert", description="Type de transaction (optionnel, défaut: transfert)")
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Frais calculés",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="success", type="boolean", example=true),
 *             @OA\Property(property="message", type="string", example="Frais calculés avec succès"),
 *             @OA\Property(property="data", type="object",
 *                 @OA\Property(property="montant", type="integer", example=3000),
 *                 @OA\Property(property="type_transaction", type="string", example="transfert"),
 *                 @OA\Property(property="frais_calcules", type="number", format="float", example=24)
 *             )
 *         )
 *     ),
 *     @OA\Response(
 *         response=401,
 *         description="Non authentifié",
 *         @OA\JsonContent(ref="#/components/schemas/ErrorResponse")
 *     )
 * )
 */
class AdminApi{}