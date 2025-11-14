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
 *             @OA\Property(property="telephone", type="string", example="+221781157773", description="Numéro de téléphone sénégalais (format: +221XXXXXXXXX)")
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
 * /**
 * @OA\Post(
 *     path="/client/login",
 *     summary="Envoyer OTP pour connexion",
 *     description="Envoie un code OTP au client actif pour se connecter",
 *     operationId="clientLogin",
 *     tags={"Client"},
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"telephone"},
 *             @OA\Property(property="telephone", type="string", example="+221781157773", description="Numéro de téléphone du client")
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="OTP envoyé avec succès",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="success", type="boolean", example=true),
 *             @OA\Property(property="message", type="string", example="Code OTP envoyé avec succès")
 *         )
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="Client non trouvé",
 *         @OA\JsonContent(ref="#/components/schemas/ErrorResponse")
 *     ),
 *     @OA\Response(
 *         response=400,
 *         description="Compte non activé",
 *         @OA\JsonContent(ref="#/components/schemas/ErrorResponse")
 *     )
 * )
 * @OA\Post(
 *     path="/client/verify-otp",
 *     summary="Vérifier OTP et authentifier",
 *     description="Vérifie le code OTP et active le compte ou connecte le client selon le type",
 *     operationId="verifyOtpNew",
 *     tags={"Client"},
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"telephone", "otp"},
 *             @OA\Property(property="telephone", type="string", example="+221781157773", description="Numéro de téléphone du client"),
 *             @OA\Property(property="otp", type="string", example="482913", description="Code OTP à 6 chiffres")
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
 *                 @OA\Property(property="access_token", type="string", description="Token d'accès OAuth2"),
 *                 @OA\Property(property="refresh_token", type="string", description="Token de rafraîchissement"),
 *                 @OA\Property(property="token_type", type="string", example="Bearer")
 *             )
 *         )
 *     ),
 *     @OA\Response(
 *         response=401,
 *         description="OTP invalide ou expiré",
 *         @OA\JsonContent(ref="#/components/schemas/ErrorResponse")
 *     )
 * )
 *
 * @OA\Get(
 *     path="/client/compte",
 *     summary="Récupérer le profil client",
 *     description="Récupère les informations du client connecté, son compte, ses transactions et son QR code",
 *     operationId="getClientProfile",
 *     tags={"Client"},
 *     security={{"bearerAuth": {}}},
 *     @OA\Response(
 *         response=200,
 *         description="Profil client récupéré avec succès",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="success", type="boolean", example=true),
 *             @OA\Property(property="message", type="string", example="Profil client récupéré avec succès"),
 *             @OA\Property(property="data", type="object",
 *                 @OA\Property(property="client", type="object",
 *                     @OA\Property(property="id", type="string", example="uuid-client"),
 *                     @OA\Property(property="nom", type="string", example="Diallo"),
 *                     @OA\Property(property="prenom", type="string", example="Moussa"),
 *                     @OA\Property(property="telephone", type="string", example="+221781157773"),
 *                     @OA\Property(property="nci", type="string", example="1234567890123"),
 *                     @OA\Property(property="statut", type="string", example="actif")
 *                 ),
 *                 @OA\Property(property="compte", type="object",
 *                     @OA\Property(property="numero_compte", type="string", example="+221781157773"),
 *                     @OA\Property(property="solde", type="integer", example=12500),
 *                     @OA\Property(property="statut", type="string", example="actif")
 *                 ),
 *                 @OA\Property(property="transactions", type="array",
 *                     @OA\Items(type="object",
 *                         @OA\Property(property="type", type="string", example="transfert", enum={"transfert", "reception", "retrait", "paiement"}),
 *                         @OA\Property(property="telephone", type="string", example="+221781157774"),
 *                         @OA\Property(property="montant", type="integer", example=-3000),
 *                         @OA\Property(property="date_transaction", type="string", format="date-time", example="2025-11-12T10:35:42Z")
 *                     )
 *                 ),
 *                 @OA\Property(property="qrcode", type="string", example="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAA...")
 *             )
 *         )
 *     ),
 *     @OA\Response(
 *         response=401,
 *         description="Client non authentifié",
 *         @OA\JsonContent(ref="#/components/schemas/ErrorResponse")
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="Aucun compte trouvé",
 *         @OA\JsonContent(ref="#/components/schemas/ErrorResponse")
 *     )
 * )
 *
 * @OA\Get(
 *     path="/client/{numero}/solde",
 *     summary="Récupérer le solde d'un compte",
 *     description="Récupère le solde actuel d'un compte spécifique appartenant au client connecté",
 *     operationId="getAccountBalance",
 *     tags={"Client"},
 *     security={{"bearerAuth": {}}},
 *     @OA\Parameter(
 *         name="numero",
 *         in="path",
 *         required=true,
 *         description="Numéro du compte",
 *         @OA\Schema(type="string", example="+221781157773")
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Solde récupéré avec succès",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="status", type="string", example="success"),
 *             @OA\Property(property="message", type="string", example="Solde récupéré avec succès"),
 *             @OA\Property(property="data", type="object",
 *                 @OA\Property(property="compte_id", type="string", format="uuid", example="uuid-compte"),
 *                 @OA\Property(property="numero_compte", type="string", example="+221781157773"),
 *                 @OA\Property(property="solde", type="integer", example=12500),
 *                 @OA\Property(property="devise", type="string", example="XOF")
 *             )
 *         )
 *     ),
 *     @OA\Response(
 *         response=401,
 *         description="Client non authentifié",
 *         @OA\JsonContent(ref="#/components/schemas/ErrorResponse")
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="Compte non trouvé ou accès non autorisé",
 *         @OA\JsonContent(ref="#/components/schemas/ErrorResponse")
 *     ),
 *     @OA\Response(
 *         response=403,
 *         description="Compte inactif",
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


 */
class AuthApi{}