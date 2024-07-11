<?php

namespace Tests\Unit;

use App\Services\GithubProvider;
use App\Services\GitlabProvider;
use App\Services\RepositorySearchService;
use Exception;
use Tests\TestCase;
use Mockery;

class RepositorySearchServiceTest extends TestCase
{
    private $githubProvider;
    private $gitlabProvider;
    private RepositorySearchService $repositorySearchService;

    public function setUp(): void
    {
        parent::setUp();

        $this->githubProvider = Mockery::mock(GithubProvider::class);
        $this->gitlabProvider = Mockery::mock(GitlabProvider::class);
        $this->repositorySearchService = new RepositorySearchService($this->githubProvider, $this->gitlabProvider);
    }

    /**
     * Test that search returns results from both providers.
     * @test
     */
    public function searchReturnsResultsFromBothProviders()
    {
        $query = 'test';
        $githubResponse = ['github_repo1', 'github_repo2'];
        $gitlabResponse = ['gitlab_repo1', 'gitlab_repo2'];

        $this->githubProvider->shouldReceive('search')->with($query)->andReturn($githubResponse);
        $this->gitlabProvider->shouldReceive('search')->with($query)->andReturn($gitlabResponse);

        $result = $this->repositorySearchService->search($query);

        $this->assertEquals([
            'github' => $githubResponse,
            'gitlab' => $gitlabResponse,
        ], $result);
    }
}
