<?php

declare(strict_types=1);

namespace SapB1\Toolkit\DTOs\BusinessPartner;

use SapB1\Toolkit\DTOs\Base\BaseDto;
use SapB1\Toolkit\Enums\BoYesNo;

/**
 * @phpstan-consistent-constructor
 */
final class SalesPersonDto extends BaseDto
{
    public function __construct(
        public readonly ?int $salesEmployeeCode = null,
        public readonly ?string $salesEmployeeName = null,
        public readonly ?float $commissionForSalesEmployee = null,
        public readonly ?int $commissionGroup = null,
        public readonly ?BoYesNo $locked = null,
        public readonly ?int $employeeID = null,
        public readonly ?BoYesNo $active = null,
        public readonly ?string $telephone = null,
        public readonly ?string $mobile = null,
        public readonly ?string $email = null,
        public readonly ?string $fax = null,
        public readonly ?string $remarks = null,
    ) {}

    protected static function mapFromArray(array $data): array
    {
        $locked = null;
        if (isset($data['Locked'])) {
            $locked = BoYesNo::tryFrom($data['Locked']);
        }

        $active = null;
        if (isset($data['Active'])) {
            $active = BoYesNo::tryFrom($data['Active']);
        }

        return [
            'salesEmployeeCode' => $data['SalesEmployeeCode'] ?? null,
            'salesEmployeeName' => $data['SalesEmployeeName'] ?? null,
            'commissionForSalesEmployee' => isset($data['CommissionForSalesEmployee']) ? (float) $data['CommissionForSalesEmployee'] : null,
            'commissionGroup' => $data['CommissionGroup'] ?? null,
            'locked' => $locked,
            'employeeID' => $data['EmployeeID'] ?? null,
            'active' => $active,
            'telephone' => $data['Telephone'] ?? null,
            'mobile' => $data['Mobile'] ?? null,
            'email' => $data['E_Mail'] ?? null,
            'fax' => $data['Fax'] ?? null,
            'remarks' => $data['Remarks'] ?? null,
        ];
    }

    public function toArray(): array
    {
        return array_filter([
            'SalesEmployeeCode' => $this->salesEmployeeCode,
            'SalesEmployeeName' => $this->salesEmployeeName,
            'CommissionForSalesEmployee' => $this->commissionForSalesEmployee,
            'CommissionGroup' => $this->commissionGroup,
            'Locked' => $this->locked?->value,
            'EmployeeID' => $this->employeeID,
            'Active' => $this->active?->value,
            'Telephone' => $this->telephone,
            'Mobile' => $this->mobile,
            'E_Mail' => $this->email,
            'Fax' => $this->fax,
            'Remarks' => $this->remarks,
        ], fn ($value) => $value !== null);
    }
}
