<?php

declare(strict_types=1);

namespace App\Services;

use Exception;

class RepositoryFormatter
{
    /**
     * Format the repositories' data.
     *
     * @param array $repositories The raw repository data.
     * @param string $provider The provider type (gitlab or github).
     * @return array The formatted repository data.
     * @throws Exception If an error occurs.
     */
    public function format(array $repositories, string $provider): array
    {
        $return = [];
        foreach ($repositories as $r) {
            $ownerKey = $provider === 'gitlab' ? 'namespace' : 'owner';
            $ownerSubKey = $provider === 'gitlab' ? 'name' : 'login';

            if (!isset($r[$ownerKey][$ownerSubKey])) {
                throw new Exception("Repository owner information is missing.");
            }

            $return[] = [
                'name' => $r['name'],
                'full_name' => $provider === 'gitlab' ? $r['path_with_namespace'] : $r['full_name'],
                'description' => $r['description'],
                'owner' => $r[$ownerKey][$ownerSubKey],
            ];
        }

        return $return;
    }
}
