<?php

namespace App\Http\Controllers;

use App\Services\CompteService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class SoldeController extends Controller
{
    protected $compteService;

    public function __construct(CompteService $compteService)
    {
        $this->compteService = $compteService;
    }

    public function getSolde(): JsonResponse
    {
        try {
            $soldeData = $this->compteService->getSoldeForClient();

            return response()->json([
                'success' => true,
                'data' => $soldeData
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 404);
        }
    }
}