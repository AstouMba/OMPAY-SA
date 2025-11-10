<?php

namespace App\Repository;

use App\Models\Transaction;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class TransactionRepository
{
    private Transaction $transaction;

    public function __construct(Transaction $transaction)
    {
        $this->transaction = $transaction;
    }

    /**
     * Récupère toutes les transactions
     */
    public function all(): Collection
    {
        return $this->transaction->all();
    }

    /**
     * Récupère toutes les transactions avec pagination
     */
    public function paginate(int $perPage = 15): LengthAwarePaginator
    {
        return $this->transaction->paginate($perPage);
    }

    /**
     * Récupère les transactions d'un client spécifique avec pagination
     */
    public function getByClientId(string $clientId, int $perPage = 15): LengthAwarePaginator
    {
        return $this->transaction
            ->join('comptes', 'transactions.compte_id', '=', 'comptes.id')
            ->where('comptes.client_id', $clientId)
            ->select('transactions.*')
            ->paginate($perPage);
    }

    /**
     * Récupère les transactions d'un client avec filtres et tri par date décroissante
     */
    public function getClientTransactionsWithFilters(string $clientId, array $filters = [], int $perPage = 15): LengthAwarePaginator
    {
        $query = $this->transaction
            ->join('comptes', 'transactions.compte_id', '=', 'comptes.id')
            ->where('comptes.client_id', $clientId)
            ->select('transactions.*')
            ->orderBy('transactions.created_at', 'desc');

        // Appliquer les filtres de date
        if (isset($filters['date_debut'])) {
            $query->whereDate('transactions.created_at', '>=', $filters['date_debut']);
        }

        if (isset($filters['date_fin'])) {
            $query->whereDate('transactions.created_at', '<=', $filters['date_fin']);
        }

        // Filtre par type de transaction
        if (isset($filters['type'])) {
            $query->where('transactions.type', $filters['type']);
        }

        // Filtre par statut
        if (isset($filters['statut'])) {
            $query->where('transactions.statut', $filters['statut']);
        }

        // Filtre par montant
        if (isset($filters['montant_min'])) {
            $query->where('transactions.montant', '>=', $filters['montant_min']);
        }

        if (isset($filters['montant_max'])) {
            $query->where('transactions.montant', '<=', $filters['montant_max']);
        }

        return $query->paginate($perPage);
    }

    /**
     * Récupère les transactions d'un compte spécifique
     */
    public function getByCompteId(string $compteId, int $perPage = 15): LengthAwarePaginator
    {
        return $this->transaction
            ->where('compte_id', $compteId)
            ->paginate($perPage);
    }

    /**
     * Recherche des transactions avec filtres
     */
    public function search(array $filters, int $perPage = 15): LengthAwarePaginator
    {
        $query = $this->transaction->query();

        if (isset($filters['type'])) {
            $query->where('type', $filters['type']);
        }

        if (isset($filters['statut'])) {
            $query->where('statut', $filters['statut']);
        }

        if (isset($filters['date_debut']) && isset($filters['date_fin'])) {
            $query->whereBetween('created_at', [$filters['date_debut'], $filters['date_fin']]);
        }

        if (isset($filters['montant_min'])) {
            $query->where('montant', '>=', $filters['montant_min']);
        }

        if (isset($filters['montant_max'])) {
            $query->where('montant', '<=', $filters['montant_max']);
        }

        return $query->paginate($perPage);
    }

    /**
     * Récupère les transactions par type
     */
    public function getByType(string $type, int $perPage = 15): LengthAwarePaginator
    {
        return $this->transaction
            ->where('type', $type)
            ->paginate($perPage);
    }

    /**
     * Récupère les transactions par statut
     */
    public function getByStatus(string $status, int $perPage = 15): LengthAwarePaginator
    {
        return $this->transaction
            ->where('statut', $status)
            ->paginate($perPage);
    }

    /**
     * Récupère les transactions dans une période donnée
     */
    public function getByDateRange(string $startDate, string $endDate, int $perPage = 15): LengthAwarePaginator
    {
        return $this->transaction
            ->whereBetween('created_at', [$startDate, $endDate])
            ->paginate($perPage);
    }
}