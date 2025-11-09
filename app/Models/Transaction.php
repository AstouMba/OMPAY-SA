<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Utils\GenererUuid;

class Transaction extends Model
{
    use HasFactory, GenererUuid;

    protected $table = 'transactions';
    public $incrementing = false; 
    protected $keyType = 'string';

    protected $fillable = [
        'compte_id',
        'type', 
        'montant',
        'devise',
        'description',
        'statut' 
    ];

    public function compte()
    {
        return $this->belongsTo(Compte::class, 'compte_id');
    }
    public function marchand()
{
    return $this->belongsTo(Marchand::class, 'marchand_id');
}


    public function scopeValidees($query)
    {
        return $query->where('statut', 'validee');
    }

    public function scopeDepot($query)
    {
        return $query->where('type', 'depot');
    }

    public function scopeRetrait($query)
    {
        return $query->whereIn('type', ['retrait', 'paiement_marchand', 'transfert_ompay']);
    }

    public function scopeType($query, $type)
    {
        return $query->where('type', $type);
    }

    public function scopeDateEntre($query, $debut, $fin)
    {
        return $query->whereBetween('created_at', [$debut, $fin]);
    }
}
