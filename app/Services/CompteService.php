<?php

namespace App\Services;

use App\Repository\CompteRepository;
use App\Models\Compte;

class CompteService
{
    protected $compteRepository;

    public function __construct(CompteRepository $compteRepository)
    {
        $this->compteRepository = $compteRepository;
    }

    public function getSoldeForClient()
    {
        // Pour le test, récupérer le premier compte du premier client
        $compte = $this->compteRepository->getCompteByClientFirst();

        if (!$compte) {
            throw new \Exception('Aucun compte trouvé pour ce client.');
        }

        $solde = $compte->solde();
        $devise = $compte->devise ?? 'FCFA';
        $dateMiseAJour = now()->toISOString();

        return [
            'solde' => $solde,
            'devise' => $devise,
            'date_mise_a_jour' => $dateMiseAJour
        ];
    }

    public function getSoldeForCompte($compteId)
    {
        $compte = $this->compteRepository->getCompteById($compteId);

        if (!$compte) {
            throw new \Exception('Compte non trouvé.');
        }

        return $this->getSoldeForCompteInstance($compte);
    }

    protected function getSoldeForCompteInstance(Compte $compte)
    {
        $solde = $compte->solde();
        $devise = $compte->devise ?? 'FCFA';
        $dateMiseAJour = now()->toISOString();

        return [
            'solde' => $solde,
            'devise' => $devise,
            'date_mise_a_jour' => $dateMiseAJour
        ];
    }
}
