<?php

namespace App\Http\Controllers;
use App\Services\TransactionService;
use App\Traits\ApiResponses;


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
    return $this->successResponse($transaction,"liste des transactions recupérée avec succés",200);
} catch (\Exception $e) {
    return $this->errorResponse("erreur lors de la recupération des transactions",500);
}

}
}
