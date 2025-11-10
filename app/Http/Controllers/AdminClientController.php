<?php

namespace App\Http\Controllers;

use App\Services\AdminClientService;
use App\Traits\ApiResponses;
use App\Enums\MessageEnumFr;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminClientController extends Controller
{
    use ApiResponses;

    protected $adminClientService;

    public function __construct(AdminClientService $adminClientService)
    {
        $this->adminClientService = $adminClientService;
    }

    public function create(Request $request)
    {
        $validator = $this->adminClientService->validateData($request->all());

        if ($validator->fails()) {
            return $this->errorResponse($validator->errors()->first(), Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        try {
            $result = $this->adminClientService->createClientAndAccount($request->all());

            return $this->successResponse([
                'client' => $result['client'],
                'compte' => $result['compte'],
            ], MessageEnumFr::CLIENT_COMPTE_CREE);

        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), Response::HTTP_UNPROCESSABLE_ENTITY);
        }
    }
}
