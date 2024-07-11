<?php

declare(strict_types=1);

namespace App\Http\Contracts;

interface RepositoryProvider
{
    // Search for repositories based on a query string
    public function search(string $query): array;
}
