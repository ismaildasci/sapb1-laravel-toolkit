<?php

declare(strict_types=1);

namespace SapB1\Toolkit\Models\BusinessPartner;

use SapB1\Toolkit\Enums\CardType;
use SapB1\Toolkit\Models\QueryBuilder;
use SapB1\Toolkit\Models\Relations\HasMany;
use SapB1\Toolkit\Models\Sales\Invoice;
use SapB1\Toolkit\Models\Sales\Order;
use SapB1\Toolkit\Models\SapB1Model;

/**
 * Business Partner model.
 *
 * @property string|null $CardCode
 * @property string|null $CardName
 * @property CardType|null $CardType
 * @property int|null $GroupCode
 * @property string|null $Address
 * @property string|null $ZipCode
 * @property string|null $City
 * @property string|null $Country
 * @property string|null $Phone1
 * @property string|null $Phone2
 * @property string|null $Cellular
 * @property string|null $Fax
 * @property string|null $EmailAddress
 * @property string|null $Currency
 * @property string|null $FederalTaxID
 * @property string|null $Notes
 * @property bool|null $Valid
 * @property float|null $CurrentAccountBalance
 * @property float|null $OpenDeliveryNotesBalance
 * @property float|null $OpenOrdersBalance
 */
class Partner extends SapB1Model
{
    protected static string $entity = 'BusinessPartners';

    protected static string $primaryKey = 'CardCode';

    protected array $fillable = [
        'CardCode',
        'CardName',
        'CardType',
        'GroupCode',
        'Address',
        'ZipCode',
        'City',
        'Country',
        'Phone1',
        'Phone2',
        'Cellular',
        'Fax',
        'EmailAddress',
        'Currency',
        'FederalTaxID',
        'Notes',
        'Valid',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'GroupCode' => 'integer',
            'CardType' => CardType::class,
            'CurrentAccountBalance' => 'decimal:2',
            'OpenDeliveryNotesBalance' => 'decimal:2',
            'OpenOrdersBalance' => 'decimal:2',
            'Valid' => 'boolean',
        ];
    }

    /**
     * Get sales orders for this partner.
     */
    public function orders(): HasMany
    {
        return $this->hasMany(Order::class, 'CardCode', 'CardCode');
    }

    /**
     * Get invoices for this partner.
     */
    public function invoices(): HasMany
    {
        return $this->hasMany(Invoice::class, 'CardCode', 'CardCode');
    }

    /**
     * Scope: Customers only.
     */
    public function scopeCustomers(QueryBuilder $query): QueryBuilder
    {
        return $query->where('CardType', 'cCustomer');
    }

    /**
     * Scope: Vendors only.
     */
    public function scopeVendors(QueryBuilder $query): QueryBuilder
    {
        return $query->where('CardType', 'cSupplier');
    }

    /**
     * Scope: Leads only.
     */
    public function scopeLeads(QueryBuilder $query): QueryBuilder
    {
        return $query->where('CardType', 'cLead');
    }

    /**
     * Scope: Active partners.
     */
    public function scopeActive(QueryBuilder $query): QueryBuilder
    {
        return $query->where('Valid', 'tYES');
    }

    /**
     * Scope: By group.
     */
    public function scopeByGroup(QueryBuilder $query, int $groupCode): QueryBuilder
    {
        return $query->where('GroupCode', $groupCode);
    }

    /**
     * Check if partner is a customer.
     */
    public function isCustomer(): bool
    {
        return $this->CardType === CardType::Customer ||
               $this->CardType?->value === 'cCustomer';
    }

    /**
     * Check if partner is a vendor.
     */
    public function isVendor(): bool
    {
        return $this->CardType === CardType::Supplier ||
               $this->CardType?->value === 'cSupplier';
    }

    /**
     * Check if partner is a lead.
     */
    public function isLead(): bool
    {
        return $this->CardType === CardType::Lead ||
               $this->CardType?->value === 'cLead';
    }
}
