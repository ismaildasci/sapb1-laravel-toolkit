<?php

declare(strict_types=1);

namespace SapB1\Toolkit\Models\Sales;

use SapB1\Toolkit\Enums\BlanketAgreementMethod;
use SapB1\Toolkit\Enums\BlanketAgreementStatus;
use SapB1\Toolkit\Models\BusinessPartner\Partner;
use SapB1\Toolkit\Models\Lines\BlanketAgreementItemLine;
use SapB1\Toolkit\Models\QueryBuilder;
use SapB1\Toolkit\Models\Relations\BelongsTo;
use SapB1\Toolkit\Models\Relations\HasMany;
use SapB1\Toolkit\Models\SapB1Model;

/**
 * Blanket Agreement model.
 */
class BlanketAgreement extends SapB1Model
{
    protected static string $entity = 'BlanketAgreements';

    protected static string $primaryKey = 'AgreementNo';

    protected array $fillable = [
        'BPCode',
        'BPName',
        'ContactPersonCode',
        'StartDate',
        'EndDate',
        'SigningDate',
        'Description',
        'AgreementMethod',
        'Status',
        'Owner',
        'PriceList',
        'Remarks',
        'BlanketAgreementItemsLines',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'AgreementNo' => 'integer',
            'StartDate' => 'date',
            'EndDate' => 'date',
            'SigningDate' => 'date',
            'AgreementMethod' => BlanketAgreementMethod::class,
            'Status' => BlanketAgreementStatus::class,
        ];
    }

    public function partner(): BelongsTo
    {
        return $this->belongsTo(Partner::class, 'BPCode', 'CardCode');
    }

    public function itemLines(): HasMany
    {
        return $this->hasMany(BlanketAgreementItemLine::class, 'AgreementNo', 'AgreementNo');
    }

    public function scopeActive(QueryBuilder $query): QueryBuilder
    {
        return $query->where('Status', BlanketAgreementStatus::Approved->value);
    }

    public function scopeByPartner(QueryBuilder $query, string $bpCode): QueryBuilder
    {
        return $query->where('BPCode', $bpCode);
    }

    /**
     * Set as monetary agreement.
     */
    public function asMonetary(): static
    {
        $this->AgreementMethod = BlanketAgreementMethod::Monetary;

        return $this;
    }

    /**
     * Set as quantity agreement.
     */
    public function asQuantity(): static
    {
        $this->AgreementMethod = BlanketAgreementMethod::Quantity;

        return $this;
    }

    /**
     * Terminate the agreement.
     */
    public function terminate(): bool
    {
        if (! $this->exists) {
            return false;
        }

        $this->Status = BlanketAgreementStatus::Terminated;

        return $this->save();
    }
}
