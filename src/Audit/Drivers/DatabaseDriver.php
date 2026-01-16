<?php

declare(strict_types=1);

namespace SapB1\Toolkit\Audit\Drivers;

use Illuminate\Support\Facades\DB;
use SapB1\Toolkit\Audit\AuditEntry;
use SapB1\Toolkit\Audit\Contracts\AuditDriverInterface;

/**
 * Database driver for storing audit logs.
 */
final class DatabaseDriver implements AuditDriverInterface
{
    private string $table;

    private ?string $connection;

    public function __construct(?string $table = null, ?string $connection = null)
    {
        $this->table = $table ?? config('laravel-toolkit.audit.table', 'sap_audit_logs');
        $this->connection = $connection ?? config('laravel-toolkit.audit.connection');
    }

    /**
     * Store an audit entry.
     */
    public function store(AuditEntry $entry): bool
    {
        $data = $entry->toArray();
        unset($data['id']); // Remove id for insert

        // Encode JSON fields
        $data['old_values'] = $data['old_values'] !== null ? json_encode($data['old_values']) : null;
        $data['new_values'] = $data['new_values'] !== null ? json_encode($data['new_values']) : null;
        $data['changed_fields'] = $data['changed_fields'] !== null ? json_encode($data['changed_fields']) : null;
        $data['metadata'] = ! empty($data['metadata']) ? json_encode($data['metadata']) : null;

        return $this->query()->insert($data);
    }

    /**
     * Retrieve audit entries for an entity.
     *
     * @return array<int, AuditEntry>
     */
    public function getForEntity(string $entityType, string|int $entityId): array
    {
        $records = $this->query()
            ->where('auditable_type', $entityType)
            ->where('auditable_id', (string) $entityId)
            ->orderByDesc('created_at')
            ->get();

        return $this->hydrateRecords($records->toArray());
    }

    /**
     * Retrieve audit entries by user.
     *
     * @return array<int, AuditEntry>
     */
    public function getByUser(string|int $userId, ?string $userType = null): array
    {
        $query = $this->query()->where('user_id', (string) $userId);

        if ($userType !== null) {
            $query->where('user_type', $userType);
        }

        $records = $query->orderByDesc('created_at')->get();

        return $this->hydrateRecords($records->toArray());
    }

    /**
     * Retrieve audit entries for an entity type.
     *
     * @return array<int, AuditEntry>
     */
    public function getByEntityType(string $entityType, ?string $since = null, ?int $limit = null): array
    {
        $query = $this->query()->where('auditable_type', $entityType);

        if ($since !== null) {
            $query->where('created_at', '>=', $since);
        }

        $query->orderByDesc('created_at');

        if ($limit !== null) {
            $query->limit($limit);
        }

        $records = $query->get();

        return $this->hydrateRecords($records->toArray());
    }

    /**
     * Delete old audit entries based on retention policy.
     */
    public function prune(int $days): int
    {
        $cutoff = now()->subDays($days)->toDateTimeString();

        return $this->query()->where('created_at', '<', $cutoff)->delete();
    }

    /**
     * Count entries older than given days.
     */
    public function countOlderThan(int $days): int
    {
        $cutoff = now()->subDays($days)->toDateTimeString();

        return $this->query()->where('created_at', '<', $cutoff)->count();
    }

    /**
     * Get the driver name.
     */
    public function getName(): string
    {
        return 'database';
    }

    /**
     * Get query builder for the audit table.
     */
    private function query(): \Illuminate\Database\Query\Builder
    {
        $query = DB::table($this->table);

        if ($this->connection !== null) {
            $query = DB::connection($this->connection)->table($this->table);
        }

        return $query;
    }

    /**
     * Hydrate database records into AuditEntry objects.
     *
     * @param  array<int, object|array<string, mixed>>  $records
     * @return array<int, AuditEntry>
     */
    private function hydrateRecords(array $records): array
    {
        return array_map(function ($record) {
            $data = (array) $record;

            // Decode JSON fields
            $data['old_values'] = $data['old_values'] !== null ? json_decode($data['old_values'], true) : null;
            $data['new_values'] = $data['new_values'] !== null ? json_decode($data['new_values'], true) : null;
            $data['changed_fields'] = $data['changed_fields'] !== null ? json_decode($data['changed_fields'], true) : null;
            $data['metadata'] = $data['metadata'] !== null ? json_decode($data['metadata'], true) : [];

            return AuditEntry::fromArray($data);
        }, $records);
    }
}
