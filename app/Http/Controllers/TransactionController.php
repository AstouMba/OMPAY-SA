<?php

namespace App\Http\Controllers;
use App\Services\TransactionService;
use App\Traits\ApiResponses;
use App\Enums\MessageEnumFr;
use Symfony\Component\HttpFoundation\Response;


class TransactionController extends Controller
{
    use ApiResponses;
protected TransactionService $transactionService;
public function __construct(TransactionService $transactionService){
    $this->transactionService=$transactionService;
    }
public function index(){
try {
    $transaction=$this->transactionService->all();
    return $this->successResponse($transaction, MessageEnumFr::LISTE_TRANSACTIONS_RECUPEREE);
} catch (\Exception $e) {
    return $this->errorResponse(MessageEnumFr::ERREUR_RECUPERATION_TRANSACTIONS, Response::HTTP_INTERNAL_SERVER_ERROR);
}

}
}
