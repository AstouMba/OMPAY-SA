<?php
namespace App\Repository;
use App\Models\Transaction;
class TransactionRepository{

private Transaction $transaction;
   public function __construct( Transaction $transaction){
        $this->transaction=$transaction;
    }
public function all(){
   return $this->transaction->all();
}

}