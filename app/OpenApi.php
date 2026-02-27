<?php

namespace App;

use OpenApi\Annotations as OA;

/**
 * @OA\Info(
 *     title="Laravel SaaS RBAC API",
 *     version="1.0.0",
 *     description="API documentation for the multi-tenant SaaS RBAC system"
 * )
 *
 * @OA\SecurityScheme(
 *     securityScheme="sanctum",
 *     type="http",
 *     scheme="bearer",
 *     bearerFormat="JWT"
 * )
 */
class OpenApi
{
    // Swagger global configuration
}