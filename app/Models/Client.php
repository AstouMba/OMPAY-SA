<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Utils\GenererUuid;

class Client extends Model
{
    use HasFactory, SoftDeletes, GenererUuid;

    protected $table = 'clients';
    public $incrementing = false; // UUID
    protected $keyType = 'string';

    protected $fillable = [
        'nom',
        'prenom',
        'telephone',
        'email'
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
