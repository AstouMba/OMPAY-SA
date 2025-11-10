<?php

namespace App\Services;

use App\Repository\TransactionRepository;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class TransactionService
{
    protected $transactionRepository;

    public function __construct(TransactionRepository $transactionRepository)
    {
        $this->transactionRepository = $transactionRepository;
    }

    /**
     * Récupère toutes les transactions avec pagination
     */
    public function getAllPaginated(int $perPage = 15): LengthAwarePaginator
    {
        return $this->transactionRepository->paginate($perPage);
    }

    /**
     * Récupère toutes les transactions sans pagination
     */
    public function getAll(): Collection
    {
        return $this->transactionRepository->all();
    }

    /**
     * Récupère les transactions du client connecté avec filtres optionnels et pagination
     */
    public function getTransactionsForAuthenticatedClient(string $clientId, array $filters = [], int $perPage = 15): LengthAwarePaginator
    {
        return $this->transactionRepository->getClientTransactionsWithFilters($clientId, $filters, $perPage);
    }
}