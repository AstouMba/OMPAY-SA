<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Utils\GenererUuid;

class Compte extends Model
{
    use HasFactory, GenererUuid;

    protected $table = 'comptes';
    public $incrementing = false; 
    protected $keyType = 'string';

    protected $fillable = [
        'client_id',
        'numero_compte',
        'type_compte',
        'devise',
        'est_supprime'
    ];

    public function client()
    {
        return $this->belongsTo(Client::class, 'client_id');
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class, 'compte_id');
    }

    public function scopeActif($query)
    {
        return $query->where('statut', 'actif');
    }

    public function scopeParClient($query, $clientId)
    {
        return $query->where('client_id', $clientId);
    }

    public function solde()
    {
        // Calcul des entrées (crédits)
        $entrees = $this->transactions()
            ->whereIn('type', ['depot', 'transfert_credit'])
            ->where('statut', 'validee')
            ->sum('montant');

        // Calcul des sorties (débits)
        $sorties = $this->transactions()
            ->whereIn('type', ['retrait', 'transfert_debit', 'paiement_marchand'])
            ->where('statut', 'validee')
            ->sum('montant');

        return $entrees - $sorties;
    }

    public function getSoldeAttribute()
    {
        return $this->solde();
    }
}
