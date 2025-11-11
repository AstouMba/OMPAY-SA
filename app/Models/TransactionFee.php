<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TransactionFee extends Model
{
    use HasFactory;

    protected $table = 'transaction_fees';

    protected $fillable = [
        'type_transaction',
        'pourcentage_frais',
        'frais_fixe',
        'actif'
    ];

    protected $casts = [
        'pourcentage_frais' => 'decimal:4',
        'frais_fixe' => 'decimal:2',
        'actif' => 'boolean'
    ];

    /**
     * Scope pour les frais actifs
     */
    public function scopeActif($query)
    {
        return $query->where('actif', true);
    }

    /**
     * Scope pour un type de transaction spécifique
     */
    public function scopePourType($query, $type)
    {
        return $query->where('type_transaction', $type);
    }

    /**
     * Calculer les frais pour un montant donné
     */
    public function calculerFrais($montant)
    {
        $fraisPourcentage = $montant * $this->pourcentage_frais;
        return round($fraisPourcentage + $this->frais_fixe, 2);
    }
}