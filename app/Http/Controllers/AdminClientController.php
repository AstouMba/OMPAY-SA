<?php

namespace App\Http\Controllers;

use App\Services\AdminClientService;
use App\Traits\ApiResponses;
use App\Enums\MessageEnumFr;
use App\Http\Requests\CreateClientRequest;
use Symfony\Component\HttpFoundation\Response;

class AdminClientController extends Controller
{
    use ApiResponses;

    protected $adminClientService;

    public function __construct(AdminClientService $adminClientService)
    {
        $this->adminClientService = $adminClientService;
    }

    public function create(CreateClientRequest $request)
    {
        try {
            $result = $this->adminClientService->createClientAndAccount($request->validated());

            $client = $result['client'];
            $compte = $result['compte'];

            return $this->successResponse([
                'client' => [
                    'id' => $client->id,
                    'nom' => $client->nom,
                    'prenom' => $client->prenom,
                    'telephone' => $client->telephone,
                    'nci' => $client->nci,
                    'statut' => $compte->statut,
                ],
            ], MessageEnumFr::CLIENT_COMPTE_CREE);

        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), Response::HTTP_UNPROCESSABLE_ENTITY);
        }
    }
}
