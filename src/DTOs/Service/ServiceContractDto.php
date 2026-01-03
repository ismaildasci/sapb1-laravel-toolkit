<?php

declare(strict_types=1);

namespace SapB1\Toolkit\DTOs\Service;

use SapB1\Toolkit\DTOs\Base\BaseDto;

/**
 * @phpstan-consistent-constructor
 */
final class ServiceContractDto extends BaseDto
{
    /**
     * @param  array<ServiceContractLineDto>  $serviceContractLines
     */
    public function __construct(
        public readonly ?int $contractID = null,
        public readonly ?string $customerCode = null,
        public readonly ?string $customerName = null,
        public readonly ?int $contactCode = null,
        public readonly ?string $owner = null,
        public readonly ?string $status = null,
        public readonly ?string $contractType = null,
        public readonly ?string $renewalType = null,
        public readonly ?string $reminderTime = null,
        public readonly ?string $reminderUnit = null,
        public readonly ?string $durationOfCoverage = null,
        public readonly ?string $startDate = null,
        public readonly ?string $endDate = null,
        public readonly ?string $resolutionTime = null,
        public readonly ?string $resolutionUnit = null,
        public readonly ?string $description = null,
        public readonly ?string $remarks = null,
        public readonly ?string $terminationDate = null,
        public readonly ?int $templateId = null,
        public readonly ?string $responseTime = null,
        public readonly ?string $responseUnit = null,
        public readonly array $serviceContractLines = [],
    ) {}

    protected static function mapFromArray(array $data): array
    {
        $lines = [];
        if (isset($data['ServiceContract_Lines']) && is_array($data['ServiceContract_Lines'])) {
            foreach ($data['ServiceContract_Lines'] as $line) {
                $lines[] = ServiceContractLineDto::fromArray($line);
            }
        }

        return [
            'contractID' => $data['ContractID'] ?? null,
            'customerCode' => $data['CustomerCode'] ?? null,
            'customerName' => $data['CustomerName'] ?? null,
            'contactCode' => $data['ContactCode'] ?? null,
            'owner' => $data['Owner'] ?? null,
            'status' => $data['Status'] ?? null,
            'contractType' => $data['ContractType'] ?? null,
            'renewalType' => $data['RenewalType'] ?? null,
            'reminderTime' => $data['ReminderTime'] ?? null,
            'reminderUnit' => $data['ReminderUnit'] ?? null,
            'durationOfCoverage' => $data['DurationOfCoverage'] ?? null,
            'startDate' => $data['StartDate'] ?? null,
            'endDate' => $data['EndDate'] ?? null,
            'resolutionTime' => $data['ResolutionTime'] ?? null,
            'resolutionUnit' => $data['ResolutionUnit'] ?? null,
            'description' => $data['Description'] ?? null,
            'remarks' => $data['Remarks'] ?? null,
            'terminationDate' => $data['TerminationDate'] ?? null,
            'templateId' => $data['TemplateId'] ?? null,
            'responseTime' => $data['ResponseTime'] ?? null,
            'responseUnit' => $data['ResponseUnit'] ?? null,
            'serviceContractLines' => $lines,
        ];
    }

    public function toArray(): array
    {
        $data = array_filter([
            'ContractID' => $this->contractID,
            'CustomerCode' => $this->customerCode,
            'CustomerName' => $this->customerName,
            'ContactCode' => $this->contactCode,
            'Owner' => $this->owner,
            'Status' => $this->status,
            'ContractType' => $this->contractType,
            'RenewalType' => $this->renewalType,
            'ReminderTime' => $this->reminderTime,
            'ReminderUnit' => $this->reminderUnit,
            'DurationOfCoverage' => $this->durationOfCoverage,
            'StartDate' => $this->startDate,
            'EndDate' => $this->endDate,
            'ResolutionTime' => $this->resolutionTime,
            'ResolutionUnit' => $this->resolutionUnit,
            'Description' => $this->description,
            'Remarks' => $this->remarks,
            'TerminationDate' => $this->terminationDate,
            'TemplateId' => $this->templateId,
            'ResponseTime' => $this->responseTime,
            'ResponseUnit' => $this->responseUnit,
        ], fn ($value) => $value !== null);

        if (! empty($this->serviceContractLines)) {
            $data['ServiceContract_Lines'] = array_map(
                fn (ServiceContractLineDto $line) => $line->toArray(),
                $this->serviceContractLines
            );
        }

        return $data;
    }
}
