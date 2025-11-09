<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Utils\GenererUuid;

class Compte extends Model
{
    use HasFactory, SoftDeletes, GenererUuid;

    protected $table = 'comptes';
    public $incrementing = false; 
    protected $keyType = 'string';

    protected $fillable = [
        'client_id',
        'numero_compte',
        'nom_titulaire',
        'devise',
        'statut'
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

    public function getSoldeAttribute()
    {
        $soldeDepot = $this->transactions()->depot()->sum('montant');
        $soldeRetrait = $this->transactions()->retrait()->sum('montant');
        return $soldeDepot - $soldeRetrait;
    }
}
