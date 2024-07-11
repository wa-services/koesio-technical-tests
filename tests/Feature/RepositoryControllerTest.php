<?php

declare(strict_types=1);

namespace Tests\Feature;

use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class RepositoryControllerTest extends TestCase
{
    /**
     * Test that a bad request is returned if no search query is provided.
     */
    public function test_returns_bad_request_if_no_search_query_provided(): void
    {
        $response = $this->getJson('/api/repository');

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    /**
     * Test that a bad request is returned if an empty search query is provided.
     */
    public function test_returns_bad_request_if_empty_search_query_provided(): void
    {
        $response = $this->getJson('/api/repository?q=');

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    /**
     * Test that a bad request is returned if the search query is longer than 256 characters.
     */
    public function test_returns_bad_request_if_search_query_longer_than_256_chars(): void
    {
        $response = $this->getJson('/api/repository?q='.str_repeat('a', 257));

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    /**
     * Test that a success response is returned if the search query is exactly 256 characters long.
     */
    public function test_returns_success_if_search_query_longer_equal_256_chars(): void
    {
        $response = $this->getJson('/api/repository?q='.str_repeat('a', 256));

        $response->assertStatus(Response::HTTP_OK);
    }

    /**
     * Test that the correct GitHub repository data is returned.
     */
    public function test_returns_right_github_repositories_data(): void
    {
        $given = $this->getJson('/api/repository?q=linux')->assertStatus(Response::HTTP_OK)->json()['github'];
        $expected = $this->getExpected(__DIR__.'/../Expected/Repository/linux_github_search.json');

        foreach ($expected as $repository) {
            self::assertContains($repository, $given);
        }
    }

    /**
     * Test that the correct GitLab repository data is returned.
     */
    public function test_returns_right_gitlab_repositories_data(): void
    {
        $given = $this->getJson('/api/repository?q=linux')->assertStatus(Response::HTTP_OK)->json()['gitlab'];
        $expected = $this->getExpected(__DIR__.'/../Expected/Repository/linux_gitlab_search.json');

        foreach ($expected as $repository) {
            self::assertContains($repository, $given);
        }
    }

    /**
     * Test that the correct merged repository data is returned.
     */
    public function test_returns_right_merged_repositories_data(): void
    {
        $response = $this->getJson('/api/repository?q=linux')->assertStatus(Response::HTTP_OK);

        $expectedGithub = $this->getExpected(__DIR__.'/../Expected/Repository/linux_github_search.json');
        $expectedGitlab = $this->getExpected(__DIR__.'/../Expected/Repository/linux_gitlab_search.json');

        self::assertEquals([
            'github' => $expectedGithub,
            'gitlab' => $expectedGitlab,
        ], $response->json());
    }
}
