<?php

namespace App\Docs;

use OpenApi\Annotations as OA;

/**
 * @OA\Info(
 *     title="OmPay API",
 *     version="1.0.0",
 *     description="API pour l'application OmPay - Système de paiement mobile sénégalais avec authentification double niveau",
 *     @OA\Contact(
 *         email="contact@ompay.sn"
 *     )
 * )
 *
 * @OA\Server(
 *     url="http://localhost:8000/api/v1",
 *     description="Serveur de développement"
 * )
 *
 * @OA\Server(
 *     url="https://ompay-api.onrender.com/api/v1",
 *     description="Serveur de production"
 * )
 *
 * @OA\SecurityScheme(
 *     securityScheme="bearerAuth",
 *     type="http",
 *     scheme="bearer",
 *     bearerFormat="JWT",
 *     description="Token JWT obtenu via l'authentification admin ou client"
 * )
 *
 * @OA\Tag(
 *     name="Health Check",
 *     description="Vérification de l'état de l'API"
 * )
 *
 * @OA\Get(
 *     path="/health",
 *     summary="Vérification de santé",
 *     description="Endpoint pour vérifier que l'API fonctionne correctement",
 *     operationId="healthCheck",
 *     tags={"Health Check"},
 *     @OA\Response(
 *         response=200,
 *         description="API opérationnelle",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="status", type="string", example="ok"),
 *             @OA\Property(property="timestamp", type="string", format="date-time")
 *         )
 *     )
 * )
 *
 * @OA\PathItem(
 *     path="/user"
 * )
 *
 * @OA\PathItem(
 *     path="/v1/solde"
 * )
 *
 * @OA\PathItem(
 *     path="/v1/transactions"
 * )
 *
 * @OA\PathItem(
 *     path="/v1/admin/clients"
 * )
 */
class OpenApiInfo{}