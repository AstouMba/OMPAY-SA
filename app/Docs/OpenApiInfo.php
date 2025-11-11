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
 *     url="https://ompay-sa.onrender.com/api/v1",
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
 // Health check removed from documentation as requested
 *
 * @OA\PathItem(
 *     path="/user"
 * )
 *
 
 *
 * @OA\PathItem(
 *     path="/v1/admin/clients"
 * )
 */
class OpenApiInfo{}