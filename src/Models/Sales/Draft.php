<?php

declare(strict_types=1);

namespace SapB1\Toolkit\Models\Sales;

use SapB1\Toolkit\Enums\DocumentType;
use SapB1\Toolkit\Models\BusinessPartner\Partner;
use SapB1\Toolkit\Models\Lines\DocumentLine;
use SapB1\Toolkit\Models\QueryBuilder;
use SapB1\Toolkit\Models\Relations\BelongsTo;
use SapB1\Toolkit\Models\Relations\HasMany;
use SapB1\Toolkit\Models\SapB1Model;

/**
 * Draft document model.
 */
class Draft extends SapB1Model
{
    protected static string $entity = 'Drafts';

    protected static string $primaryKey = 'DocEntry';

    protected array $fillable = [
        'CardCode',
        'CardName',
        'DocDate',
        'DocDueDate',
        'TaxDate',
        'NumAtCard',
        'Comments',
        'DocObjectCode',
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
            'VatSum' => 'decimal:2',
            'DocObjectCode' => DocumentType::class,
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

    /**
     * Scope: Sales order drafts.
     */
    public function scopeSalesOrders(QueryBuilder $query): QueryBuilder
    {
        return $query->where('DocObjectCode', DocumentType::SalesOrder->value);
    }

    /**
     * Scope: Invoice drafts.
     */
    public function scopeInvoices(QueryBuilder $query): QueryBuilder
    {
        return $query->where('DocObjectCode', DocumentType::ARInvoice->value);
    }

    /**
     * Scope: Quotation drafts.
     */
    public function scopeQuotations(QueryBuilder $query): QueryBuilder
    {
        return $query->where('DocObjectCode', DocumentType::SalesQuotation->value);
    }

    public function scopeByCustomer(QueryBuilder $query, string $cardCode): QueryBuilder
    {
        return $query->where('CardCode', $cardCode);
    }

    /**
     * Set as sales order draft.
     */
    public function asSalesOrder(): static
    {
        $this->DocObjectCode = DocumentType::SalesOrder;

        return $this;
    }

    /**
     * Set as invoice draft.
     */
    public function asInvoice(): static
    {
        $this->DocObjectCode = DocumentType::ARInvoice;

        return $this;
    }

    /**
     * Set as quotation draft.
     */
    public function asQuotation(): static
    {
        $this->DocObjectCode = DocumentType::SalesQuotation;

        return $this;
    }
}
