<?php

namespace App;

use OpenApi\Annotations as OA;

/**
 * @OA\OpenApi(
 *   @OA\Info(
 *     version="1.0.0",
 *     title="Laravel SaaS RBAC API",
 *     description="API-only SaaS with Sanctum + Spatie Permission"
 *   ),
 *   @OA\Server(
 *     url="/",
 *     description="Default"
 *   ),
 *   @OA\Components(
 *     @OA\SecurityScheme(
 *       securityScheme="sanctum",
 *       type="apiKey",
 *       in="header",
 *       name="Authorization",
 *       description="Use: Bearer {token}"
 *     )
 *   )
 * )
 */
final class OpenApi
{
}
