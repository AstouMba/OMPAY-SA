<?php

namespace App\Docs;

use OpenApi\Annotations as OA;

/**
 * @OA\Info(
 *     title="OmPay API",
 *     version="1.0.0",
 *     description="API pour le système de paiement OmPay",
 *     @OA\Contact(
 *         email="support@ompay.com"
 *     )
 * )
 *
 * @OA\Server(
 *     url="http://localhost:8000/api",
 *     description="Serveur de développement"
 * )
 *
 * @OA\SecurityScheme(
 *     securityScheme="bearerAuth",
 *     type="http",
 *     scheme="bearer",
 *     bearerFormat="JWT"
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