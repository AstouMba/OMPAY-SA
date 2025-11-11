<?php

namespace App\Http\Controllers;

use App\Models\TransactionFee;
use App\Services\FeeService;
use App\Traits\ApiResponses;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Validation\Rule;

class TransactionFeeController extends Controller
{
    use ApiResponses;

    protected FeeService $feeService;

    public function __construct(FeeService $feeService)
    {
        $this->feeService = $feeService;
    }

    /**
     * Afficher toutes les configurations de frais
     */
    public function index()
    {
        try {
            $fees = $this->feeService->getAllFeeConfigs();

            return $this->successResponse(
                $fees,
                'Configurations de frais récupérées avec succès'
            );
        } catch (\Exception $e) {
            return $this->errorResponse(
                'Erreur lors de la récupération des frais',
                Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }

    /**
     * Créer une nouvelle configuration de frais
     */
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'type_transaction' => 'required|string|max:50',
                'pourcentage_frais' => 'required|numeric|min:0|max:1',
                'frais_fixe' => 'numeric|min:0',
                'actif' => 'boolean'
            ]);

            $fee = $this->feeService->setFeeConfig(
                $validated['type_transaction'],
                $validated['pourcentage_frais'],
                $validated['frais_fixe'] ?? 0,
                $validated['actif'] ?? true
            );

            return $this->successResponse(
                $fee,
                'Configuration de frais créée avec succès',
                Response::HTTP_CREATED
            );
        } catch (\Exception $e) {
            return $this->errorResponse(
                'Erreur lors de la création de la configuration',
                Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }

    /**
     * Afficher une configuration de frais spécifique
     */
    public function show(TransactionFee $fee)
    {
        return $this->successResponse(
            $fee,
            'Configuration de frais récupérée avec succès'
        );
    }

    /**
     * Mettre à jour une configuration de frais par type
     */
    public function updateByType(Request $request, $type)
    {
        try {
            $validated = $request->validate([
                'pourcentage_frais' => 'required|numeric|min:0|max:1',
                'frais_fixe' => 'numeric|min:0',
                'actif' => 'boolean'
            ]);

            $fee = $this->feeService->setFeeConfig(
                $type,
                $validated['pourcentage_frais'],
                $validated['frais_fixe'] ?? 0,
                $validated['actif'] ?? true
            );

            return $this->successResponse(
                $fee,
                'Configuration de frais mise à jour avec succès'
            );
        } catch (\Exception $e) {
            return $this->errorResponse(
                'Erreur lors de la mise à jour de la configuration',
                Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }

    /**
     * Supprimer une configuration de frais
     */
    public function destroy(TransactionFee $fee)
    {
        try {
            $fee->delete();

            return $this->successResponse(
                null,
                'Configuration de frais supprimée avec succès'
            );
        } catch (\Exception $e) {
            return $this->errorResponse(
                'Erreur lors de la suppression de la configuration',
                Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }

    /**
     * Activer/désactiver une configuration de frais
     */
    public function toggleActive(TransactionFee $fee)
    {
        try {
            $fee->update(['actif' => !$fee->actif]);

            $status = $fee->actif ? 'activée' : 'désactivée';

            return $this->successResponse(
                $fee,
                "Configuration de frais {$status} avec succès"
            );
        } catch (\Exception $e) {
            return $this->errorResponse(
                'Erreur lors de la modification du statut',
                Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }

    /**
     * Calculer les frais pour un montant donné
     */
    public function calculate(Request $request)
    {
        try {
            $validated = $request->validate([
                'montant' => 'required|numeric|min:0',
                'type_transaction' => 'string|max:50'
            ]);

            $fees = $this->feeService->calculateFees(
                $validated['montant'],
                $validated['type_transaction'] ?? 'transfert'
            );

            return $this->successResponse([
                'montant' => $validated['montant'],
                'type_transaction' => $validated['type_transaction'] ?? 'transfert',
                'frais_calcules' => $fees
            ], 'Frais calculés avec succès');

        } catch (\Exception $e) {
            return $this->errorResponse(
                'Erreur lors du calcul des frais',
                Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }
}