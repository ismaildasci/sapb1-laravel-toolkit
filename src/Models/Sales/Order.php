<?php

declare(strict_types=1);

namespace SapB1\Toolkit\Models\Sales;

use SapB1\Toolkit\Enums\DocumentStatus;
use SapB1\Toolkit\Models\BusinessPartner\Partner;
use SapB1\Toolkit\Models\Lines\DocumentLine;
use SapB1\Toolkit\Models\QueryBuilder;
use SapB1\Toolkit\Models\Relations\BelongsTo;
use SapB1\Toolkit\Models\Relations\HasMany;
use SapB1\Toolkit\Models\SapB1Model;

/**
 * Sales Order model.
 *
 * @property int|null $DocEntry
 * @property int|null $DocNum
 * @property string|null $CardCode
 * @property string|null $CardName
 * @property string|null $DocDate
 * @property string|null $DocDueDate
 * @property string|null $TaxDate
 * @property string|null $NumAtCard
 * @property string|null $Comments
 * @property int|null $SalesPersonCode
 * @property int|null $ContactPersonCode
 * @property string|null $ShipToCode
 * @property string|null $PayToCode
 * @property float|null $DocTotal
 * @property float|null $DocTotalFc
 * @property float|null $VatSum
 * @property float|null $VatSumFc
 * @property float|null $DiscountPercent
 * @property DocumentStatus|null $DocumentStatus
 * @property array<int, array<string, mixed>>|null $DocumentLines
 */
class Order extends SapB1Model
{
    protected static string $entity = 'Orders';

    protected static string $primaryKey = 'DocEntry';

    protected array $fillable = [
        'CardCode',
        'CardName',
        'DocDate',
        'DocDueDate',
        'TaxDate',
        'NumAtCard',
        'Comments',
        'SalesPersonCode',
        'ContactPersonCode',
        'ShipToCode',
        'PayToCode',
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
            'DocTotal' => 'decimal:2',
            'DocTotalFc' => 'decimal:2',
            'VatSum' => 'decimal:2',
            'VatSumFc' => 'decimal:2',
            'DiscountPercent' => 'decimal:2',
            'DocumentStatus' => DocumentStatus::class,
        ];
    }

    /**
     * Get the business partner.
     */
    public function partner(): BelongsTo
    {
        return $this->belongsTo(Partner::class, 'CardCode', 'CardCode');
    }

    /**
     * Get document lines.
     */
    public function documentLines(): HasMany
    {
        return $this->hasMany(DocumentLine::class, 'DocEntry', 'DocEntry');
    }

    /**
     * Scope: Open orders.
     */
    public function scopeOpen(QueryBuilder $query): QueryBuilder
    {
        return $query->where('DocumentStatus', 'bost_Open');
    }

    /**
     * Scope: Closed orders.
     */
    public function scopeClosed(QueryBuilder $query): QueryBuilder
    {
        return $query->where('DocumentStatus', 'bost_Close');
    }

    /**
     * Scope: By customer.
     */
    public function scopeByCustomer(QueryBuilder $query, string $cardCode): QueryBuilder
    {
        return $query->where('CardCode', $cardCode);
    }

    /**
     * Scope: By date range.
     *
     * @param  array{0: string, 1: string}  $range
     */
    public function scopeDateBetween(QueryBuilder $query, array $range): QueryBuilder
    {
        return $query->whereBetween('DocDate', $range);
    }

    /**
     * Close the order.
     */
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
     * Cancel the order.
     */
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
     * Copy to delivery note.
     */
    public function toDelivery(): Delivery
    {
        $delivery = new Delivery;
        $delivery->fill([
            'CardCode' => $this->CardCode,
            'CardName' => $this->CardName,
            'NumAtCard' => $this->NumAtCard,
            'Comments' => $this->Comments,
        ]);

        // Map lines with base reference
        $lines = [];
        foreach ($this->DocumentLines ?? [] as $line) {
            $lineData = is_array($line) ? $line : $line->toArray();
            $lines[] = [
                'BaseType' => 17, // Sales Order
                'BaseEntry' => $this->DocEntry,
                'BaseLine' => $lineData['LineNum'] ?? 0,
            ];
        }

        $delivery->DocumentLines = $lines;

        return $delivery;
    }

    /**
     * Copy to invoice.
     */
    public function toInvoice(): Invoice
    {
        $invoice = new Invoice;
        $invoice->fill([
            'CardCode' => $this->CardCode,
            'CardName' => $this->CardName,
            'NumAtCard' => $this->NumAtCard,
            'Comments' => $this->Comments,
        ]);

        // Map lines with base reference
        $lines = [];
        foreach ($this->DocumentLines ?? [] as $line) {
            $lineData = is_array($line) ? $line : $line->toArray();
            $lines[] = [
                'BaseType' => 17, // Sales Order
                'BaseEntry' => $this->DocEntry,
                'BaseLine' => $lineData['LineNum'] ?? 0,
            ];
        }

        $invoice->DocumentLines = $lines;

        return $invoice;
    }
}
