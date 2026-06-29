<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ValidateApiOrigin
{
    public function handle(Request $request, Closure $next): Response
    {
        if (! in_array($request->method(), ['POST', 'PUT', 'PATCH', 'DELETE'], true)) {
            return $next($request);
        }

        if (app()->environment('testing') && ! $request->headers->has('Origin') && ! $request->headers->has('Referer')) {
            return $next($request);
        }

        $allowed = array_values(array_unique(array_filter([
            $this->normalizeOrigin((string) config('portfolio.frontend_url')),
            $this->normalizeOrigin((string) config('app.url')),
        ])));

        if ($this->matchesAllowed($request->headers->get('Origin'), $allowed)) {
            return $next($request);
        }

        if ($this->matchesAllowed($request->headers->get('Referer'), $allowed)) {
            return $next($request);
        }

        abort(403, 'Forbidden.');
    }

    private function matchesAllowed(?string $value, array $allowed): bool
    {
        if (! $value) {
            return false;
        }

        $normalized = $this->normalizeOrigin($value);

        foreach ($allowed as $origin) {
            if ($normalized === $origin || str_starts_with($normalized, $origin.'/')) {
                return true;
            }
        }

        return false;
    }

    private function normalizeOrigin(string $value): string
    {
        $value = trim($value);

        if ($value === '') {
            return '';
        }

        if (! str_contains($value, '://')) {
            $value = 'https://'.$value;
        }

        $parts = parse_url($value);
        if (! is_array($parts) || empty($parts['host'])) {
            return rtrim($value, '/');
        }

        $scheme = $parts['scheme'] ?? 'https';
        $host = $parts['host'];
        $port = isset($parts['port']) ? ':'.$parts['port'] : '';

        return rtrim("{$scheme}://{$host}{$port}", '/');
    }
}
