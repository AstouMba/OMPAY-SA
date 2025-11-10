<?php

namespace App\Docs;

use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *     schema="Client",
 *     type="object",
 *     title="Client",
 *     description="Modèle représentant un client",
 *     @OA\Property(property="id", type="string", format="uuid", description="ID unique du client"),
 *     @OA\Property(property="nom", type="string", description="Nom du client"),
 *     @OA\Property(property="prenom", type="string", description="Prénom du client"),
 *     @OA\Property(property="telephone", type="string", description="Numéro de téléphone"),
 *     @OA\Property(property="nci", type="string", description="Numéro de carte d'identité"),
 *     @OA\Property(property="date_creation", type="string", format="date-time", description="Date de création"),
 *     @OA\Property(property="date_modification", type="string", format="date-time", description="Date de mise à jour"),
 *     @OA\Property(property="comptes", type="array", @OA\Items(ref="#/components/schemas/Compte"), description="Liste des comptes du client")
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
 *     @OA\Property(property="est_supprime", type="boolean", description="Statut de suppression"),
 *     @OA\Property(property="date_creation", type="string", format="date-time", description="Date de création"),
 *     @OA\Property(property="date_modification", type="string", format="date-time", description="Date de mise à jour"),
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
 *     @OA\Property(property="montant", type="number", format="float", description="Montant de la transaction"),
 *     @OA\Property(property="statut", type="string", enum={"en_attente", "validee", "annulee"}, description="Statut de la transaction"),
 *     @OA\Property(property="date_creation", type="string", format="date-time", description="Date de création"),
 *     @OA\Property(property="date_modification", type="string", format="date-time", description="Date de mise à jour")
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
 *     @OA\Property(property="date_modification", type="string", format="date-time", description="Date de mise à jour")
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
 *         @OA\Property(property="client", ref="#/components/schemas/Client"),
 *         @OA\Property(property="compte", ref="#/components/schemas/Compte")
 *     )
 * )
 */
class Schemas {}
