<?php

namespace App\Http\Controllers;

use App\Services\AdminClientService;
use App\Traits\ApiResponses;
use Illuminate\Http\Request;

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
            return $this->errorResponse($validator->errors()->first(), 422);
        }

        try {
            $result = $this->adminClientService->createClientAndAccount($request->all());

            return $this->successResponse([
                'client' => $result['client'],
                'compte' => $result['compte'],
            ], 'Client et compte créés avec succès.');

        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 422);
        }
    }
}
