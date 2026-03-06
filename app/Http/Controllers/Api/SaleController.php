<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Sale;
use App\Services\SaleService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use OpenApi\Annotations as OA;

class SaleController extends Controller
{
    public function __construct(
        private readonly SaleService $saleService,
    ) {
    }

    /**
     * @OA\Get(
     *   path="/api/sales",
     *   summary="List sales for the authenticated user's company",
     *   tags={"Sales"},
     *   security={{"sanctum":{}}},
     *   @OA\Response(response=200, description="List of sales")
     * )
     */
    public function index(Request $request): JsonResponse
    {
        $user = $request->user();

        $sales = $this->saleService->listForUser($user);

        return response()->json($sales);
    }

    /**
     * @OA\Post(
     *   path="/api/sales",
     *   summary="Create a new sale for a client in the authenticated user's company",
     *   tags={"Sales"},
     *   security={{"sanctum":{}}},
     *   @OA\RequestBody(
     *     required=true,
     *     @OA\JsonContent(
     *       required={"client_id"},
     *       @OA\Property(property="client_id", type="integer")
     *     )
     *   ),
     *   @OA\Response(response=201, description="Sale created"),
     *   @OA\Response(response=422, description="Validation error")
     * )
     */
    public function store(Request $request): JsonResponse
    {
        $user = $request->user();

        $validated = $request->validate([
            'client_id' => ['required', 'integer'],
        ]);

        $sale = $this->saleService->create($user, $validated);

        return response()->json([
            'data' => $sale,
            'message' => 'Sale created successfully.',
        ], 201);
    }

    /**
     * @OA\Post(
     *   path="/api/sales/{id}/approve",
     *   summary="Approve a sale",
     *   tags={"Sales"},
     *   security={{"sanctum":{}}},
     *   @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *   @OA\Response(response=200, description="Sale approved"),
     *   @OA\Response(response=403, description="Forbidden")
     * )
     */
    public function approve(Request $request, Sale $sale): JsonResponse
    {
        $user = $request->user();

        $sale = $this->saleService->approve($user, $sale);

        return response()->json([
            'data' => $sale,
            'message' => 'Sale approved successfully.',
        ]);
    }
}