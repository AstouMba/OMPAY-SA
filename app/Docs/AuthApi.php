<?php
namespace App\Docs;
use OpenApi\Annotations as OA;

/**
 * @OA\Tag(
 *     name="Authentification Client",
 *     description="Opérations d'authentification pour les clients"
 * )
 *
 * @OA\Post(
 *     path="/client/send-otp",
 *     summary="Envoyer un code OTP",
 *     description="Envoie un code OTP au numéro de téléphone du client pour l'authentification",
 *     operationId="sendOtp",
 *     tags={"Authentification Client"},
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"telephone"},
 *             @OA\Property(property="telephone", type="string", description="Numéro de téléphone sénégalais (format: +221XXXXXXXXX)")
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Code OTP envoyé avec succès",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="success", type="boolean", example=true),
 *             @OA\Property(property="message", type="string", example="Code OTP envoyé avec succès"),
 *             @OA\Property(property="data", type="object",
 *                 @OA\Property(property="otp", type="string", description="Code OTP (uniquement en développement)")
 *             )
 *         )
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="Client non trouvé",
 *         @OA\JsonContent(ref="#/components/schemas/ErrorResponse")
 *     ),
 *     @OA\Response(
 *         response=403,
 *         description="Le client ne possède pas de compte",
 *         @OA\JsonContent(ref="#/components/schemas/ErrorResponse")
 *     ),
 *     @OA\Response(
 *         response=422,
 *         description="Numéro de téléphone invalide",
 *         @OA\JsonContent(ref="#/components/schemas/ErrorResponse")
 *     )
 * )
 *
 * @OA\Post(
 *     path="/client/verify-otp",
 *     summary="Vérifier le code OTP",
 *     description="Vérifie le code OTP et connecte le client si valide",
 *     operationId="verifyOtp",
 *     tags={"Authentification Client"},
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"telephone", "otp"},
 *             @OA\Property(property="telephone", type="string", description="Numéro de téléphone sénégalais"),
 *             @OA\Property(property="otp", type="string", minLength=6, maxLength=6, description="Code OTP à 6 chiffres")
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
 *                 @OA\Property(property="client", ref="#/components/schemas/Client"),
 *                 @OA\Property(property="access_token", type="string", description="Token d'accès"),
 *                 @OA\Property(property="token_type", type="string", example="Bearer")
 *             )
 *         )
 *     ),
 *     @OA\Response(
 *         response=401,
 *         description="Code OTP invalide ou expiré",
 *         @OA\JsonContent(ref="#/components/schemas/ErrorResponse")
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="Client non trouvé",
 *         @OA\JsonContent(ref="#/components/schemas/ErrorResponse")
 *     )
 * )
 *
 * @OA\Post(
 *     path="/client/logout",
 *     summary="Déconnexion client",
 *     description="Déconnecte le client actuellement connecté",
 *     operationId="clientLogout",
 *     tags={"Authentification Client"},
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
 *     path="/client/user",
 *     summary="Informations du client connecté",
 *     description="Récupère les informations du client actuellement connecté avec ses comptes",
 *     operationId="getClientUser",
 *     tags={"Authentification Client"},
 *     security={{"bearerAuth": {}}},
 *     @OA\Response(
 *         response=200,
 *         description="Informations récupérées",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="success", type="boolean", example=true),
 *             @OA\Property(property="data", ref="#/components/schemas/Client")
 *         )
 *     ),
 *     @OA\Response(
 *         response=401,
 *         description="Non authentifié",
 *         @OA\JsonContent(ref="#/components/schemas/ErrorResponse")
 *     )
 * )
 *
 

 */
class AuthApi{}