<?php

namespace App\Http\Controllers\Concerns;

trait ResolvesImageUrl
{
    protected function resolveImageUrl(?string $rawPath): string
    {
        if (!$rawPath) {
            return asset('images/no-image.png');
        }

        if (str_starts_with($rawPath, 'http://') || str_starts_with($rawPath, 'https://')) {
            return $rawPath;
        }

        $path = ltrim($rawPath, '/');

        return asset(str_starts_with($path, 'storage/') ? $path : 'storage/' . $path);
    }
}
