<?php

// Script pour vérifier les données de test créées
require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Client;
use App\Models\Compte;
use App\Models\Transaction;
use App\Models\Marchand;

echo "=== VÉRIFICATION DES DONNÉES DE TEST OMPay ===\n\n";

// Vérifier les clients
$clients = Client::all();
echo "📱 CLIENTS (" . $clients->count() . "):\n";
foreach ($clients as $client) {
    echo "  • {$client->nom} {$client->prenom} - {$client->telephone}\n";
}

echo "\n";

// Vérifier les comptes
$comptes = Compte::where('type_compte', 'ompay')->get();
echo "💰 COMPTES OMPay (" . $comptes->count() . "):\n";
foreach ($comptes as $compte) {
    $soldeCalcule = $compte->solde; // Utilise l'accessor
    echo "  • {$compte->numero_compte} - Client: {$compte->client->nom} {$compte->client->prenom}\n";
    echo "    Solde affiché: {$soldeCalcule} FCFA\n";
    
    // Compter les transactions
    $nbTransactions = Transaction::where('compte_id', $compte->id)->count();
    echo "    Transactions: {$nbTransactions}\n";
}

echo "\n";

// Vérifier les marchands
$marchands = Marchand::all();
echo "🏪 MARCHANDS (" . $marchands->count() . "):\n";
foreach ($marchands as $marchand) {
    echo "  • {$marchand->nom} - {$marchand->telephone} (Code: {$marchand->code_marchand})\n";
}

echo "\n";

// Vérifier les transactions
$transactions = Transaction::all();
echo "💳 TRANSACTIONS (" . $transactions->count() . "):\n";
$transactionsParType = $transactions->groupBy('type');
foreach ($transactionsParType as $type => $transactionsType) {
    echo "  • {$type}: " . $transactionsType->count() . " transaction(s)\n";
}

echo "\n=== TEST COMPLET ! ===\n";
echo "Tous les clients peuvent maintenant être utilisés pour tester l'application OmPay.\n";
echo "Consultez TESTING_GUIDE.md pour les instructions de test.\n";