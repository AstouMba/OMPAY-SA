<?php

namespace App\Services;

use App\Repository\ClientRepository;
use App\Repository\CompteRepository;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Rules\TelephoneSenegalRule;
use App\Rules\NciRule;

class AdminClientService
{
    protected $clientRepository;
    protected $compteRepository;

    public function __construct(ClientRepository $clientRepository, CompteRepository $compteRepository)
    {
        $this->clientRepository = $clientRepository;
        $this->compteRepository = $compteRepository;
    }

    public function validateData(array $data)
    {
        $validator = Validator::make($data, [
            'nom' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'telephone' => ['required', new TelephoneSenegalRule()],
            'nci' => ['required', 'unique:clients,nci', new NciRule()],
        ]);

        return $validator;
    }

    public function createClientAndAccount(array $data)
    {
        // Vérifier si le numéro de compte existe déjà
        $existingCompte = $this->compteRepository->findByNumero($data['telephone']);
        if ($existingCompte) {
            throw new \Exception('Le numéro de compte existe déjà.');
        }

        $result = DB::transaction(function () use ($data) {
            // Créer ou récupérer le client
            $client = $this->clientRepository->firstOrCreate([
                'nom' => $data['nom'],
                'prenom' => $data['prenom'],
                'telephone' => $data['telephone'],
                'nci' => $data['nci'],
            ]);

            // Créer le compte
            $compte = $this->compteRepository->create([
                'client_id' => $client->id,
                'numero_compte' => $data['telephone'],
                'type_compte' => 'ompay',
                'devise' => 'FCFA',
                'est_supprime' => false,
            ]);

            return [
                'client' => $client,
                'compte' => $compte,
            ];
        });

        return $result;
    }
}