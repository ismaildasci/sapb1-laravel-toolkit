<?php

declare(strict_types=1);

namespace SapB1\Toolkit\Audit;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Support\Facades\Auth;

/**
 * Value object representing the context of an audit action.
 *
 * Captures who, when, and where information for audit trails.
 */
final readonly class AuditContext
{
    public function __construct(
        public ?string $userId = null,
        public ?string $userType = null,
        public ?string $ipAddress = null,
        public ?string $userAgent = null,
        public ?string $tenantId = null,
        /** @var array<string, mixed> */
        public array $metadata = [],
    ) {}

    /**
     * Create context from current request and authenticated user.
     *
     * @param  array<string, mixed>  $metadata
     */
    public static function capture(array $metadata = []): self
    {
        $user = Auth::user();
        $request = request();

        return new self(
            userId: $user !== null ? (string) $user->getAuthIdentifier() : null,
            userType: $user !== null ? $user::class : null,
            ipAddress: $request->ip(),
            userAgent: $request->userAgent(),
            tenantId: self::resolveTenantId(),
            metadata: $metadata,
        );
    }

    /**
     * Create context for a specific user.
     *
     * @param  array<string, mixed>  $metadata
     */
    public static function forUser(Authenticatable $user, array $metadata = []): self
    {
        $request = request();

        return new self(
            userId: (string) $user->getAuthIdentifier(),
            userType: $user::class,
            ipAddress: $request->ip(),
            userAgent: $request->userAgent(),
            tenantId: self::resolveTenantId(),
            metadata: $metadata,
        );
    }

    /**
     * Create context for system/automated actions.
     *
     * @param  array<string, mixed>  $metadata
     */
    public static function system(array $metadata = []): self
    {
        return new self(
            userId: 'system',
            userType: 'system',
            ipAddress: null,
            userAgent: 'System Process',
            tenantId: self::resolveTenantId(),
            metadata: array_merge(['automated' => true], $metadata),
        );
    }

    /**
     * Create an anonymous context.
     *
     * @param  array<string, mixed>  $metadata
     */
    public static function anonymous(array $metadata = []): self
    {
        $request = request();

        return new self(
            userId: null,
            userType: null,
            ipAddress: $request->ip(),
            userAgent: $request->userAgent(),
            tenantId: self::resolveTenantId(),
            metadata: $metadata,
        );
    }

    /**
     * Convert to array for storage.
     *
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return [
            'user_id' => $this->userId,
            'user_type' => $this->userType,
            'ip_address' => $this->ipAddress,
            'user_agent' => $this->userAgent,
            'tenant_id' => $this->tenantId,
            'metadata' => $this->metadata,
        ];
    }

    /**
     * Create from array.
     *
     * @param  array<string, mixed>  $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            userId: $data['user_id'] ?? null,
            userType: $data['user_type'] ?? null,
            ipAddress: $data['ip_address'] ?? null,
            userAgent: $data['user_agent'] ?? null,
            tenantId: $data['tenant_id'] ?? null,
            metadata: $data['metadata'] ?? [],
        );
    }

    /**
     * Resolve current tenant ID if multi-tenant is enabled.
     */
    private static function resolveTenantId(): ?string
    {
        if (! config('laravel-toolkit.multi_tenant.enabled', false)) {
            return null;
        }

        try {
            $multiTenant = app(\SapB1\Toolkit\MultiTenant\MultiTenantService::class);

            return $multiTenant->getTenantId();
        } catch (\Throwable) {
            return null;
        }
    }
}
