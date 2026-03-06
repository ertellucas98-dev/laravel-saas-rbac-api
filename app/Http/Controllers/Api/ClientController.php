<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Services\ClientService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use OpenApi\Annotations as OA;

class ClientController extends Controller
{
    public function __construct(
        private readonly ClientService $clientService,
    ) {
    }

    /**
     * @OA\Get(
     *   path="/api/clients",
     *   summary="List clients for the authenticated user's company",
     *   tags={"Clients"},
     *   security={{"sanctum":{}}},
     *   @OA\Response(response=200, description="List of clients")
     * )
     */
    public function index(Request $request): JsonResponse
    {
        $user = $request->user();

        $clients = $this->clientService->listForUser($user);

        return response()->json([
            'data' => $clients->items(),
            'links' => [
                'first' => $clients->url(1),
                'last' => $clients->url($clients->lastPage()),
                'prev' => $clients->previousPageUrl(),
                'next' => $clients->nextPageUrl(),
            ],
            'meta' => [
                'current_page' => $clients->currentPage(),
                'from' => $clients->firstItem(),
                'last_page' => $clients->lastPage(),
                'per_page' => $clients->perPage(),
                'to' => $clients->lastItem(),
                'total' => $clients->total(),
            ],
        ]);
    }

    /**
     * @OA\Post(
     *   path="/api/clients",
     *   summary="Create a new client for the authenticated user's company",
     *   tags={"Clients"},
     *   security={{"sanctum":{}}},
     *   @OA\RequestBody(
     *     required=true,
     *     @OA\JsonContent(
     *       required={"name"},
     *       @OA\Property(property="name", type="string", maxLength=255)
     *     )
     *   ),
     *   @OA\Response(response=201, description="Client created"),
     *   @OA\Response(response=422, description="Validation error")
     * )
     */
    public function store(Request $request): JsonResponse
    {
        $user = $request->user();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
        ]);

        $client = $this->clientService->create($user, $validated);

        return response()->json([
            'data' => $client,
            'message' => 'Client created successfully.',
        ], 201);
    }

    /**
     * @OA\Put(
     *   path="/api/clients/{id}",
     *   summary="Update a client",
     *   tags={"Clients"},
     *   security={{"sanctum":{}}},
     *   @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *   @OA\RequestBody(
     *     required=true,
     *     @OA\JsonContent(
     *       required={"name"},
     *       @OA\Property(property="name", type="string", maxLength=255)
     *     )
     *   ),
     *   @OA\Response(response=200, description="Client updated"),
     *   @OA\Response(response=403, description="Forbidden"),
     *   @OA\Response(response=422, description="Validation error")
     * )
     */
    public function update(Request $request, Client $client): JsonResponse
    {
        $user = $request->user();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
        ]);

        $this->clientService->ensureClientBelongsToUserCompany($user, $client);

        $client = $this->clientService->update($user, $client, $validated);

        return response()->json([
            'data' => $client,
            'message' => 'Client updated successfully.',
        ]);
    }

    /**
     * @OA\Delete(
     *   path="/api/clients/{id}",
     *   summary="Delete a client",
     *   tags={"Clients"},
     *   security={{"sanctum":{}}},
     *   @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *   @OA\Response(response=204, description="Client deleted"),
     *   @OA\Response(response=403, description="Forbidden")
     * )
     */
    public function destroy(Request $request, Client $client): JsonResponse
    {
        $user = $request->user();

        $this->clientService->ensureClientBelongsToUserCompany($user, $client);

        $this->clientService->delete($user, $client);

        return response()->json(null, 204);
    }
}
