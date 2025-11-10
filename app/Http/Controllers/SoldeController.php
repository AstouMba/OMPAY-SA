<?php

namespace App\Http\Controllers;

use App\Services\CompteService;
use App\Traits\ApiResponses;
use App\Enums\MessageEnumFr;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class SoldeController extends Controller
{
    use ApiResponses;

    protected $compteService;

    public function __construct(CompteService $compteService)
    {
        $this->compteService = $compteService;
    }

    public function getSolde(): JsonResponse
    {
        try {
            $soldeData = $this->compteService->getSoldeForClient();

            return $this->successResponse($soldeData, MessageEnumFr::SOLDE_RECUPERE);
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), Response::HTTP_NOT_FOUND);
        }
    }
}