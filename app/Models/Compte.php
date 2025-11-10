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
        $depots = $this->transactions()->depot()->validees()->sum('montant');
        $transfertsEntrants = $this->transactions()->transfertEntrant()->validees()->sum('montant');
        $paiementsRecus = $this->transactions()->paiementRecu()->validees()->sum('montant'); // Assuming depot and transfert_credit are positive

        $retraits = $this->transactions()->retrait()->validees()->sum('montant');
        $transfertsSortants = $this->transactions()->transfertSortant()->validees()->sum('montant');
        $paiementsEnvoyes = $this->transactions()->where('type', 'paiement_marchand')->validees()->sum('montant');

        return ($depots + $transfertsEntrants + $paiementsRecus) - ($retraits + $transfertsSortants + $paiementsEnvoyes);
    }

    public function getSoldeAttribute()
    {
        return $this->solde();
    }
}
