<?php

declare(strict_types=1);

namespace SapB1\Toolkit\Models\Purchase;

use SapB1\Toolkit\Enums\DocumentStatus;
use SapB1\Toolkit\Models\BusinessPartner\Partner;
use SapB1\Toolkit\Models\Lines\DocumentLine;
use SapB1\Toolkit\Models\QueryBuilder;
use SapB1\Toolkit\Models\Relations\BelongsTo;
use SapB1\Toolkit\Models\Relations\HasMany;
use SapB1\Toolkit\Models\SapB1Model;

/**
 * Purchase Quotation (RFQ) model.
 */
class PurchaseQuotation extends SapB1Model
{
    protected static string $entity = 'PurchaseQuotations';

    protected static string $primaryKey = 'DocEntry';

    protected array $fillable = [
        'CardCode',
        'CardName',
        'DocDate',
        'DocDueDate',
        'TaxDate',
        'RequiredDate',
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
            'VatSum' => 'decimal:2',
            'DocumentStatus' => DocumentStatus::class,
        ];
    }

    public function partner(): BelongsTo
    {
        return $this->belongsTo(Partner::class, 'CardCode', 'CardCode');
    }

    public function documentLines(): HasMany
    {
        return $this->hasMany(DocumentLine::class, 'DocEntry', 'DocEntry');
    }

    public function scopeOpen(QueryBuilder $query): QueryBuilder
    {
        return $query->where('DocumentStatus', 'bost_Open');
    }

    public function scopeByVendor(QueryBuilder $query, string $cardCode): QueryBuilder
    {
        return $query->where('CardCode', $cardCode);
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

    /**
     * Copy to purchase order.
     */
    public function toPurchaseOrder(): PurchaseOrder
    {
        $order = new PurchaseOrder();
        $order->fill([
            'CardCode' => $this->CardCode,
            'CardName' => $this->CardName,
            'Comments' => $this->Comments,
        ]);

        $lines = [];
        foreach ($this->DocumentLines ?? [] as $line) {
            $lineData = is_array($line) ? $line : $line->toArray();
            $lines[] = [
                'BaseType' => 540000006, // Purchase Quotation
                'BaseEntry' => $this->DocEntry,
                'BaseLine' => $lineData['LineNum'] ?? 0,
            ];
        }

        $order->DocumentLines = $lines;

        return $order;
    }
}
