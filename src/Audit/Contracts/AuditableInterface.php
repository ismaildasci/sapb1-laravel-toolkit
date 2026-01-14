<?php

declare(strict_types=1);

namespace SapB1\Toolkit\Audit\Contracts;

/**
 * Interface for auditable entities.
 */
interface AuditableInterface
{
    /**
     * Check if auditing is enabled for this entity.
     */
    public static function isAuditEnabled(): bool;

    /**
     * Get fields to exclude from audit logging.
     *
     * @return array<int, string>
     */
    public function getAuditExclude(): array;

    /**
     * Get fields to always include in audit logging.
     *
     * @return array<int, string>
     */
    public function getAuditInclude(): array;

    /**
     * Get the events that should trigger auditing.
     *
     * @return array<int, string>
     */
    public function getAuditEvents(): array;

    /**
     * Get the entity identifier for auditing.
     */
    public function getAuditIdentifier(): string|int|null;

    /**
     * Get the entity type name for auditing.
     */
    public function getAuditTypeName(): string;
}
