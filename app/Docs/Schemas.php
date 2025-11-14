<?php

namespace App\Docs;

use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *     schema="Client",
 *     type="object",
 *     title="Client",
 *     description="Modèle représentant un client",
 *     @OA\Property(property="id", type="string", format="uuid", example="3fa85f64-5717-4562-b3fc-2c963f66afa6", description="ID unique du client"),
 *     @OA\Property(property="nom", type="string", example="string", description="Nom du client"),
 *     @OA\Property(property="prenom", type="string", example="string", description="Prénom du client"),
 *     @OA\Property(property="telephone", type="string", example="string", description="Numéro de téléphone"),
 *     @OA\Property(property="nci", type="string", example="string", description="Numéro de carte d'identité"),
 *     @OA\Property(property="statut", type="string", enum={"actif", "inactif"}, example="inactif", description="Statut du compte associé")
 * )
 *
 * @OA\Schema(
 *     schema="Compte",
 *     type="object",
 *     title="Compte",
 *     description="Modèle représentant un compte bancaire",
 *     @OA\Property(property="id", type="string", format="uuid", description="ID unique du compte"),
 *     @OA\Property(property="numero_compte", type="string", description="Numéro du compte"),
 *     @OA\Property(property="client", ref="#/components/schemas/Client", description="Informations du client propriétaire"),
 *     @OA\Property(property="type_compte", type="string", description="Type de compte"),
 *     @OA\Property(property="devise", type="string", description="Devise du compte"),
 *     @OA\Property(property="solde", type="number", format="float", description="Solde actuel du compte"),
 *     @OA\Property(property="statut", type="string", enum={"actif", "inactif"}, description="Statut du compte"),
 *     @OA\Property(property="date_creation", type="string", format="date-time", description="Date de création"),
 *     @OA\Property(property="transactions", type="array", @OA\Items(ref="#/components/schemas/Transaction"), description="Liste des transactions du compte")
 * )
 *
 * @OA\Schema(
 *     schema="Transaction",
 *     type="object",
 *     title="Transaction",
 *     description="Modèle représentant une transaction",
 *     @OA\Property(property="id", type="string", format="uuid", description="ID unique de la transaction"),
 *     @OA\Property(property="compte_id", type="string", format="uuid", description="ID du compte"),
 *     @OA\Property(property="marchand", ref="#/components/schemas/Marchand", description="Informations du marchand (optionnel)"),
 *     @OA\Property(property="telephone_marchand", type="string", description="Téléphone du marchand (optionnel)"),
 *     @OA\Property(property="type", type="string", enum={"depot", "retrait", "transfert_credit", "transfert_debit", "paiement_marchand"}, description="Type de transaction"),
 *     @OA\Property(property="montant", type="string", description="Montant de la transaction (avec préfixe + ou - selon le type)"),
 *     @OA\Property(property="statut", type="string", enum={"en_attente", "validee", "annulee"}, description="Statut de la transaction"),
 *     @OA\Property(property="date_creation", type="string", format="date-time", description="Date de création"),
 * )

 * @OA\Schema(
 *     schema="Marchand",
 *     type="object",
 *     title="Marchand",
 *     description="Modèle représentant un marchand",
 *     @OA\Property(property="id", type="string", format="uuid", description="ID unique du marchand"),
 *     @OA\Property(property="nom", type="string", description="Nom du marchand"),
 *     @OA\Property(property="code_marchand", type="string", description="Code unique du marchand"),
 *     @OA\Property(property="telephone", type="string", description="Numéro de téléphone du marchand"),
 *     @OA\Property(property="date_creation", type="string", format="date-time", description="Date de création"),
 * )
 *
 * @OA\Schema(
 *     schema="SoldeResponse",
 *     type="object",
 *     title="Réponse Solde",
 *     description="Réponse contenant les informations de solde",
 *     @OA\Property(property="success", type="boolean", description="Statut de succès"),
 *     @OA\Property(property="data", type="object",
 *         @OA\Property(property="solde", type="number", format="float", description="Solde actuel"),
 *         @OA\Property(property="devise", type="string", description="Devise"),
 *         @OA\Property(property="date_mise_a_jour", type="string", format="date-time", description="Date de mise à jour")
 *     )
 * )
 *
 * @OA\Schema(
 *     schema="ErrorResponse",
 *     type="object",
 *     title="Réponse d'erreur",
 *     description="Réponse en cas d'erreur",
 *     @OA\Property(property="success", type="boolean", example=false, description="Statut de succès"),
 *     @OA\Property(property="message", type="string", description="Message d'erreur")
 * )
 *
 * @OA\Schema(
 *     schema="CreateClientRequest",
 *     type="object",
 *     title="Requête de création de client",
 *     description="Données requises pour créer un client et son compte",
 *     required={"nom", "prenom", "telephone", "nci"},
 *     @OA\Property(property="nom", type="string", description="Nom du client"),
 *     @OA\Property(property="prenom", type="string", description="Prénom du client"),
 *     @OA\Property(property="telephone", type="string", description="Numéro de téléphone (format sénégalais)"),
 *     @OA\Property(property="nci", type="string", description="Numéro de carte d'identité")
 * )
 *
 * @OA\Schema(
 *     schema="CreateClientResponse",
 *     type="object",
 *     title="Réponse de création de client",
 *     description="Réponse après création d'un client et compte",
 *     @OA\Property(property="success", type="boolean", example=true, description="Statut de succès"),
 *     @OA\Property(property="message", type="string", description="Message de succès"),
 *     @OA\Property(property="data", type="object",
 *         @OA\Property(property="client", ref="#/components/schemas/Client")
 *     )
 * )
 *
 * @OA\Schema(
 *     schema="User",
 *     type="object",
 *     title="Utilisateur Administrateur",
 *     description="Modèle représentant un utilisateur administrateur",
 *     @OA\Property(property="id", type="integer", description="ID unique de l'utilisateur"),
 *     @OA\Property(property="name", type="string", description="Nom de l'utilisateur"),
 *     @OA\Property(property="email", type="string", format="email", description="Email de l'utilisateur"),
 *     @OA\Property(property="role", type="string", enum={"admin", "user"}, description="Rôle de l'utilisateur"),
 *     @OA\Property(property="email_verified_at", type="string", format="date-time", nullable=true, description="Date de vérification email"),
 *   @OA\Property(property="created_at", type="string", format="date-time", description="Date de création"),
 * )
 *
 * @OA\Schema(
 *     schema="LoginRequest",
 *     type="object",
 *     title="Requête de connexion",
 *     description="Données pour l'authentification",
 *     required={"email", "password"},
 *     @OA\Property(property="email", type="string", format="email", description="Email de l'utilisateur"),
 *     @OA\Property(property="password", type="string", format="password", description="Mot de passe")
 * )
 *
 * @OA\Schema(
 *     schema="OtpRequest",
 *     type="object",
 *     title="Requête d'envoi OTP",
 *     description="Données pour l'envoi d'un code OTP",
 *     required={"telephone"},
 *     @OA\Property(property="telephone", type="string", description="Numéro de téléphone sénégalais (format: +221XXXXXXXXX)")
 * )
 *
 * @OA\Schema(
 *     schema="VerifyOtpRequest",
 *     type="object",
 *     title="Requête de vérification OTP",
 *     description="Données pour vérifier le code OTP",
 *     required={"telephone", "otp"},
 *     @OA\Property(property="telephone", type="string", description="Numéro de téléphone sénégalais"),
 *     @OA\Property(property="otp", type="string", minLength=6, maxLength=6, description="Code OTP à 6 chiffres")
 * )
 *
 * @OA\Schema(
 *     schema="AuthResponse",
 *     type="object",
 *     title="Réponse d'authentification",
 *     description="Réponse après authentification réussie",
 *     @OA\Property(property="success", type="boolean", example=true, description="Statut de succès"),
 *     @OA\Property(property="message", type="string", description="Message de succès"),
 *     @OA\Property(property="data", type="object",
 *         @OA\Property(property="user", ref="#/components/schemas/User", description="Informations utilisateur (admin)"),
 *         @OA\Property(property="client", ref="#/components/schemas/Client", description="Informations client"),
 *         @OA\Property(property="access_token", type="string", description="Token d'accès JWT"),
 *         @OA\Property(property="refresh_token", type="string", description="Token de rafraîchissement (id du token, si applicable)"), *     )
 * )
 *
 * @OA\Schema(
 *     schema="ClientQrResponse",
 *     type="object",
 *     title="Réponse QR Code Client",
 *     description="Réponse contenant le numéro de compte et le QR Code en Base64",
 *     @OA\Property(property="numero_compte", type="string", example="771234567", description="Numéro du compte client"),
 *     @OA\Property(property="qr_code_base64", type="string", example="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAA...", description="QR Code encodé en Base64 avec préfixe data URI")
 * )
 */
class Schemas {}
