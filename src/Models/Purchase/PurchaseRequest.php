<?php

declare(strict_types=1);

namespace SapB1\Toolkit\Models\Purchase;

use SapB1\Toolkit\Enums\DocumentStatus;
use SapB1\Toolkit\Models\Lines\DocumentLine;
use SapB1\Toolkit\Models\QueryBuilder;
use SapB1\Toolkit\Models\Relations\HasMany;
use SapB1\Toolkit\Models\SapB1Model;

/**
 * Purchase Request model.
 */
class PurchaseRequest extends SapB1Model
{
    protected static string $entity = 'PurchaseRequests';

    protected static string $primaryKey = 'DocEntry';

    protected array $fillable = [
        'DocDate',
        'DocDueDate',
        'TaxDate',
        'RequiredDate',
        'Requester',
        'RequesterEmail',
        'RequesterDepartment',
        'RequesterBranch',
        'Comments',
        'DocumentLines',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'DocEntry' => 'integer',
            'DocNum' => 'integer',
            'DocDate' => 'date',
            'DocDueDate' => 'date',
            'TaxDate' => 'date',
            'RequiredDate' => 'date',
            'DocTotal' => 'decimal:2',
            'DocumentStatus' => DocumentStatus::class,
        ];
    }

    public function documentLines(): HasMany
    {
        return $this->hasMany(DocumentLine::class, 'DocEntry', 'DocEntry');
    }

    public function scopeOpen(QueryBuilder $query): QueryBuilder
    {
        return $query->where('DocumentStatus', 'bost_Open');
    }

    public function scopeByRequester(QueryBuilder $query, string $requester): QueryBuilder
    {
        return $query->where('Requester', $requester);
    }

    public function scopeByDepartment(QueryBuilder $query, int $department): QueryBuilder
    {
        return $query->where('RequesterDepartment', $department);
    }

    public function close(): bool
    {
        if (! $this->exists) {
            return false;
        }

        $client = $this->getClient();
        $client->service(static::$entity)->action($this->getKey(), 'Close');
        $this->refresh();

        return true;
    }

    public function cancel(): bool
    {
        if (! $this->exists) {
            return false;
        }

        $client = $this->getClient();
        $client->service(static::$entity)->action($this->getKey(), 'Cancel');
        $this->refresh();

        return true;
    }

    /**
     * Copy to purchase order.
     */
    public function toPurchaseOrder(): PurchaseOrder
    {
        $order = new PurchaseOrder;

        $lines = [];
        foreach ($this->DocumentLines ?? [] as $line) {
            $lineData = is_array($line) ? $line : $line->toArray();
            $lines[] = [
                'BaseType' => 1470000113, // Purchase Request
                'BaseEntry' => $this->DocEntry,
                'BaseLine' => $lineData['LineNum'] ?? 0,
            ];
        }

        $order->DocumentLines = $lines;

        return $order;
    }
}
