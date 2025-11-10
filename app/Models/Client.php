<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Utils\GenererUuid;

class Client extends Model
{
    use HasFactory, GenererUuid;

    protected $table = 'clients';
    public $incrementing = false; // UUID
    protected $keyType = 'string';

    protected $fillable = [
        'nom',
        'prenom',
        'telephone',
        'nci'
    ];

    // Relation avec Compte (OMPay)
    public function comptes()
    {
        return $this->hasMany(Compte::class, 'client_id');
    }

    // Relation avec transactions via le compte
    public function transactions()
    {
        return $this->hasManyThrough(
            Transaction::class,
            Compte::class,
            'client_id', 
            'compte_id', 
            'id',        
            'id'        
        );
    }

    // Scope pour chercher par téléphone
    public function scopeParTelephone($query, $tel)
    {
        return $query->where('telephone', $tel);
    }
}
