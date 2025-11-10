<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Passport\HasApiTokens;
use App\Utils\GenererUuid;

class Client extends Authenticatable
{
    use HasFactory, HasApiTokens, GenererUuid;

    protected $table = 'clients';
    public $incrementing = false; // UUID
    protected $keyType = 'string';

    protected $fillable = [
        'nom',
        'prenom',
        'telephone',
        'nci',
        'otp_code',
        'otp_expires_at'
    ];

    protected $hidden = [
        'otp_code',
        'otp_expires_at',
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

    /**
     * Find the client instance for the given username (telephone).
     */
    public function findForPassport($username)
    {
        return $this->where('telephone', $username)->first();
    }

    /**
     * Validate the password of the user for the Passport password grant.
     */
    public function validateForPassportPasswordGrant($password)
    {
        return $this->otp_code === $password && $this->otp_expires_at > now();
    }
}
