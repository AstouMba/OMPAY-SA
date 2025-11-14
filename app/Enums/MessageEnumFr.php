<?php
namespace App\Enums;

class MessageEnumFr{

const COMPTE_CREE='compte crée avec succés';
const ERREUR_CREATION_COMPTE='erreur lors de la creation du compte';
const LISTE_COMPTES_RECUPÉRÉE='Liste des comptes récupérée avec succès';
const ERREUR_RECUPERATION_LISTE_COMPTES='Erreur lors de la récupération des comptes';
const ISCNI = 'Numéro de CNI invalide';
const ISSENEGALPHONE = 'Numéro de téléphone invalide';
const SOLDE_RECUPERE = 'Solde récupéré avec succès';
const CLIENT_COMPTE_CREE = 'Client et compte créés avec succès. Le compte est inactif en attente d\'activation.';
const LISTE_TRANSACTIONS_RECUPEREE = 'liste des transactions recupérée avec succés';
const ERREUR_RECUPERATION_TRANSACTIONS = 'erreur lors de la recupération des transactions';

// Messages d'authentification
const LOGIN_REUSSI = 'Connexion réussie';
const LOGOUT_REUSSI = 'Déconnexion réussie';
const CREDENTIALS_INVALIDES = 'Identifiants invalides';
const ACCES_INTERDIT = 'Accès interdit';
const OTP_ENVOYE = 'Code OTP envoyé avec succès';
const OTP_INVALIDE_OU_EXPIRE = 'Code OTP invalide ou expiré';
const CLIENT_NON_TROUVE = 'Client non trouvé';
const COMPTE_NON_TROUVE = 'Le client ne possède pas de compte';





}