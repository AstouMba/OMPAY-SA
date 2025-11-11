<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Traits\ApiResponses;
use App\Http\Resources\ClientQrResource;
use App\Services\QrCodeService;

class ClientController extends Controller
{
    use ApiResponses;

    protected $qrCodeService;

    public function __construct(QrCodeService $qrCodeService)
    {
        $this->qrCodeService = $qrCodeService;
    }

    public function getQrCode()
    {
        try {
            $client = auth()->user();
            $data = $this->qrCodeService->generateClientQrCode($client);

            return $this->successResponse(new ClientQrResource($data));
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 404);
        }
    }
}
