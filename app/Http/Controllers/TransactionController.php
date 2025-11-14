<?php

namespace App\Http\Controllers;

use App\Services\TransactionService;
use App\Traits\ApiResponses;
use App\Enums\MessageEnumFr;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TransactionController extends Controller
{
    use ApiResponses;

    protected TransactionService $transactionService;

    public function __construct(TransactionService $transactionService)
    {
        $this->transactionService = $transactionService;
    }

    /**
     * Récupère les transactions du client connecté avec filtres optionnels et pagination
     */
    public function index(Request $request)
    {
        try {
            $client = auth('client')->user();
            if (!$client) {
                return $this->errorResponse('Client non authentifié', Response::HTTP_UNAUTHORIZED);
            }

            $perPage = $request->get('per_page', 15);

            // Récupérer les filtres depuis la requête
            $filters = $request->only([
                'date_debut',
                'date_fin',
                'type',
                'statut',
                'montant_min',
                'montant_max'
            ]);

            // Filtre par défaut pour les transactions validées si aucun statut spécifié
            if (!isset($filters['statut'])) {
                $filters['statut'] = 'validee';
            }

            $transactions = $this->transactionService->getTransactionsForAuthenticatedClient($client->id, $filters, $perPage);

            // Liens HATEOAS pour niveau 3 Richardson
            $compte = $client->comptes()->first();
            $links = [
                'self' => route('transactions.index', $request->query()),
                'compte' => route('client.compte'),
            ];

            if ($compte) {
                $links['solde'] = route('client.solde', ['numero' => $compte->numero_compte]);
            }

            return $this->paginatedResponse(
                $transactions,
                $links,
                MessageEnumFr::LISTE_TRANSACTIONS_RECUPEREE
            );
        } catch (\Exception $e) {
            return $this->errorResponse(
                MessageEnumFr::ERREUR_RECUPERATION_TRANSACTIONS,
                Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }

    /**
     * Effectue un paiement chez un marchand
     */
    public function payment(Request $request)
    {
        try {
            $client = auth('client')->user();
            if (!$client) {
                return $this->errorResponse('Client non authentifié', Response::HTTP_UNAUTHORIZED);
            }

            $data = $request->only(['code_marchand', 'numero_marchand', 'montant']);

            $result = $this->transactionService->processPayment($client->id, $data);

            return $this->successResponse($result, 'Paiement effectué avec succès');
        } catch (\Exception $e) {
            $message = $e->getMessage();
            if ($message === 'Solde insuffisant' || $message === 'Marchand non valide') {
                return $this->errorResponse($message, Response::HTTP_BAD_REQUEST);
            }
            return $this->errorResponse('Erreur lors du paiement', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Effectue un transfert vers un autre client OMPay
     */
    public function transfert(Request $request)
    {
        try {
            $client = auth('client')->user();
            if (!$client) {
                return $this->errorResponse('Client non authentifié', Response::HTTP_UNAUTHORIZED);
            }

            $data = $request->only(['numero_ompay', 'montant']);

            $result = $this->transactionService->processTransfert($client->id, $data);

            return $this->successResponse($result, 'Transfert effectué avec succès');
        } catch (\Exception $e) {
            $message = $e->getMessage();
            if (in_array($message, ['Solde insuffisant', 'Destinataire non valide', 'Impossible de se transférer à soi-même'])) {
                return $this->errorResponse($message, Response::HTTP_BAD_REQUEST);
            }
            return $this->errorResponse('Erreur lors du transfert', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

}
