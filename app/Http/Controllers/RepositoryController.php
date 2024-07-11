<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\RepositorySearchRequest;
use App\Services\RepositorySearchService;
use Exception;
use Illuminate\Http\JsonResponse;

class RepositoryController extends Controller
{
    private RepositorySearchService $repositorySearchService;

    public function __construct(RepositorySearchService $repositorySearchService)
    {
        $this->repositorySearchService = $repositorySearchService;
    }

    /**
     * Search for repositories on all providers.
     * @throws Exception
     */
    public function search(RepositorySearchRequest $request): JsonResponse
    {
        // Get the search query from the request
        $query = $request->get('q');

        // Perform the search
        try {
            // Perform the search on all providers
            $repositories = $this->repositorySearchService->search($query);
        } catch (Exception $e) {
            // Handle exception
            $statusCode = $e->getCode() > 0 ? $e->getCode() : 500;
            return response()->json(['error' => $e->getMessage()], $statusCode);
        }

        // Return the results
        return response()->json($repositories);
    }
}
