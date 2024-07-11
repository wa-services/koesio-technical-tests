<?php

declare(strict_types=1);

namespace App\Services;

use App\Http\Contracts\RepositoryProvider;
use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

class GithubProvider implements RepositoryProvider
{
    private Client $httpClient;
    private string $githubApiUrl;
    private int $resultsPerPage;
    private RepositoryFormatter $formatter;

    public function __construct(Client $httpClient, RepositoryFormatter $formatter)
    {
        $this->httpClient = $httpClient;
        $this->githubApiUrl = config('app.github_api_url');
        $this->resultsPerPage = (int) config('app.results_per_page');
        $this->formatter = $formatter;
    }

    /**
     * Search for repositories on GitHub.
     *
     * @param string $query The search query.
     * @return array The search results.
     * @throws Exception If an error occurs.
     */
    public function search(string $query): array
    {
        $responseBody = $this->fetchDataFromGithub($query);
        $repositories = $this->parseResponseBody($responseBody);
        return $this->formatRepositories($repositories);
    }

    /**
     * Fetch data from GitHub.
     *
     * @param string $query The search query.
     * @return string The response body.
     * @throws Exception If an error occurs.
     */
    private function fetchDataFromGithub(string $query): string
    {
        try {
            $resp = $this->httpClient->get($this->githubApiUrl . '?per_page=' . $this->resultsPerPage . '&q=' . urlencode($query));
        } catch (GuzzleException $e) {
            throw new Exception("Unable to contact API server: " . $e->getMessage(), $e->getCode(), $e);
        }

        return $resp->getBody()->getContents();
    }

    /**
     * Parse the response body.
     *
     * @param string $responseBody The response body.
     * @return array The repositories.
     * @throws Exception If an error occurs.
     */
    private function parseResponseBody(string $responseBody): array
    {
        $repositories = json_decode($responseBody, true)['items'];

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new Exception("Unable to parse JSON response: " . json_last_error_msg());
        }

        if ($repositories === null) {
            throw new Exception("No repositories found in the response.");
        }

        return $repositories;
    }

    /**
     * Format the repositories' data.
     *
     * @param array $repositories The raw repository data.
     * @return array The formatted repository data.
     * @throws Exception If an error occurs.
     */
    private function formatRepositories(array $repositories): array
    {
        return $this->formatter->format($repositories, 'github');
    }
}
