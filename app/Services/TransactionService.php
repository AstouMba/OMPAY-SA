<?php
 namespace App\Services;
 use App\Repository\TransactionRepository;
 class TransactionService{
    protected $transactionRepository;
  public function __construct(TransactionRepository $transactionRepository)
    {
        $this->transactionRepository = $transactionRepository;
    }
    public function all(){
    return $this->transactionRepository->all();
    
    }

}