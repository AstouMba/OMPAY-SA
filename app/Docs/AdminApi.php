<?php
namespace App\Docs;
use OpenApi\Annotations as OA;

/**
 * @OA\Tag(
 *     name="Administration",
 *     description="Opérations d'administration"
 * )
 *
 * @OA\Post(
 *     path="/v1/admin/clients",
 *     summary="Créer un client et son compte",
 *     description="Crée un nouveau client avec son compte associé",
 *     operationId="createClient",
 *     tags={"Administration"},
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(ref="#/components/schemas/CreateClientRequest")
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Client et compte créés avec succès",
 *         @OA\JsonContent(ref="#/components/schemas/CreateClientResponse")
 *     ),
 *     @OA\Response(
 *         response=422,
 *         description="Erreur de validation ou données invalides",
 *         @OA\JsonContent(ref="#/components/schemas/ErrorResponse")
 *     )
 * )
 */
 class AdminApi{}