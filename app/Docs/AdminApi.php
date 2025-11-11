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
 *             @OA\Property(property="email", type="string", format="email", description="Email de l'administrateur"),
 *             @OA\Property(property="password", type="string", format="password", description="Mot de passe")
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
 * @OA\Get(
 *     path="/admin/user",
 *     summary="Informations de l'administrateur connecté",
 *     description="Récupère les informations de l'administrateur actuellement connecté",
 *     operationId="getAdminUser",
 *     tags={"Administration"},
 *     security={{"bearerAuth": {}}},
 *     @OA\Response(
 *         response=200,
 *         description="Informations récupérées",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="success", type="boolean", example=true),
 *             @OA\Property(property="data", ref="#/components/schemas/User")
 *         )
 *     ),
 *     @OA\Response(
 *         response=401,
 *         description="Non authentifié",
 *         @OA\JsonContent(ref="#/components/schemas/ErrorResponse")
 *     )
 * )
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
 */
 class AdminApi{}