<?php

declare(strict_types=1);

namespace SapB1\Toolkit\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use SapB1\Toolkit\MultiTenant\MultiTenantService;
use Symfony\Component\HttpFoundation\Response;

/**
 * Middleware for resolving and setting tenant context.
 *
 * This middleware resolves the tenant from the request and sets
 * it in the MultiTenantService for the duration of the request.
 *
 * @example
 * ```php
 * // In route middleware
 * Route::middleware(['tenant'])->group(function () {
 *     Route::get('/orders', [OrderController::class, 'index']);
 * });
 *
 * // In Kernel.php
 * protected $middlewareAliases = [
 *     'tenant' => \SapB1\Toolkit\Http\Middleware\TenantMiddleware::class,
 * ];
 * ```
 */
final class TenantMiddleware
{
    /**
     * Default header name for tenant ID.
     */
    private const DEFAULT_HEADER = 'X-Tenant-ID';

    /**
     * Default query parameter name for tenant ID.
     */
    private const DEFAULT_QUERY_PARAM = 'tenant_id';

    public function __construct(
        private readonly MultiTenantService $multiTenant
    ) {}

    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): Response  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $tenantId = $this->resolveTenant($request);

        if ($tenantId !== null) {
            $this->multiTenant->setTenant($tenantId);
        }

        try {
            return $next($request);
        } finally {
            // Clear tenant after request to prevent leakage
            if ($tenantId !== null) {
                $this->multiTenant->clearTenant();
            }
        }
    }

    /**
     * Resolve tenant ID from the request.
     */
    private function resolveTenant(Request $request): ?string
    {
        // 1. Check route parameter
        $tenantId = $request->route('tenant');
        if ($tenantId !== null && is_string($tenantId)) {
            return $tenantId;
        }

        // 2. Check header
        $headerName = config('laravel-toolkit.multi_tenant.header', self::DEFAULT_HEADER);
        /** @var string|null $tenantId */
        $tenantId = $request->header($headerName);
        if ($tenantId !== null && $tenantId !== '') {
            return $tenantId;
        }

        // 3. Check query parameter
        $queryParam = config('laravel-toolkit.multi_tenant.query_param', self::DEFAULT_QUERY_PARAM);
        /** @var string|null $tenantId */
        $tenantId = $request->query($queryParam);
        if ($tenantId !== null && $tenantId !== '') {
            return $tenantId;
        }

        // 4. Check subdomain (if enabled)
        if (config('laravel-toolkit.multi_tenant.subdomain.enabled', false)) {
            $tenantId = $this->resolveFromSubdomain($request);
            if ($tenantId !== null) {
                return $tenantId;
            }
        }

        // 5. Check authenticated user (if available)
        $user = $request->user();
        if ($user !== null && isset($user->tenant_id)) {
            return $user->tenant_id;
        }

        return null;
    }

    /**
     * Resolve tenant from subdomain.
     */
    private function resolveFromSubdomain(Request $request): ?string
    {
        $host = $request->getHost();
        /** @var string $baseDomain */
        $baseDomain = config('laravel-toolkit.multi_tenant.subdomain.base_domain', '');

        if ($baseDomain === '' || ! str_ends_with($host, $baseDomain)) {
            return null;
        }

        $subdomain = str_replace('.'.$baseDomain, '', $host);

        if ($subdomain === '' || $subdomain === $host) {
            return null;
        }

        return $subdomain;
    }
}
