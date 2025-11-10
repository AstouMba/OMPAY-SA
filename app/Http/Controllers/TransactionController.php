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

            $transactions = $this->transactionService->getTransactionsForAuthenticatedClient($client->id, $filters, $perPage);

            // Liens HATEOAS pour niveau 3 Richardson
            $links = [
                'self' => route('transactions.index', $request->query()),
                'client' => route('client.user'),
                'solde' => route('client.solde'),
            ];

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

}
