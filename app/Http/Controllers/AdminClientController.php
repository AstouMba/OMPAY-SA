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

            return $this->successResponse([
                'client' => $result['client'],
                'compte' => $result['compte'],
            ], MessageEnumFr::CLIENT_COMPTE_CREE);

        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), Response::HTTP_UNPROCESSABLE_ENTITY);
        }
    }
}
