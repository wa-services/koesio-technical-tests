<?php

declare(strict_types=1);

namespace App\Services;

use Exception;

class RepositorySearchService
{
    private GithubProvider $githubProvider;
    private GitlabProvider $gitlabProvider;

    public function __construct(GithubProvider $githubProvider, GitlabProvider $gitlabProvider)
    {
        $this->githubProvider = $githubProvider;
        $this->gitlabProvider = $gitlabProvider;
    }

    /**
     * Search for repositories on all providers.
     *
     * @param string $query The search query.
     * @return array The search results.
     * @throws Exception If an error occurs.
     */
    public function search(string $query): array
    {
        // Perform the search on both providers
        $githubRepositories = $this->githubProvider->search($query);
        $gitlabRepositories = $this->gitlabProvider->search($query);

        // Merge the results
        return [
            'github' => $githubRepositories,
            'gitlab' => $gitlabRepositories,
        ];
    }
}
