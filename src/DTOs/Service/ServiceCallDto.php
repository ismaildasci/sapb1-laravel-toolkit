<?php

declare(strict_types=1);

namespace SapB1\Toolkit\DTOs\Service;

use SapB1\Toolkit\DTOs\Base\BaseDto;
use SapB1\Toolkit\Enums\ServiceCallPriority;

/**
 * @phpstan-consistent-constructor
 */
final class ServiceCallDto extends BaseDto
{
    public function __construct(
        public readonly ?int $serviceCallID = null,
        public readonly ?string $subject = null,
        public readonly ?string $customerCode = null,
        public readonly ?string $customerName = null,
        public readonly ?int $contactCode = null,
        public readonly ?string $manufacturerSerialNum = null,
        public readonly ?string $internalSerialNum = null,
        public readonly ?int $contractID = null,
        public readonly ?string $itemCode = null,
        public readonly ?string $itemDescription = null,
        public readonly ?string $itemGroup = null,
        public readonly ?int $status = null,
        public readonly ?ServiceCallPriority $priority = null,
        public readonly ?int $callType = null,
        public readonly ?int $problemType = null,
        public readonly ?int $problemSubType = null,
        public readonly ?int $origin = null,
        public readonly ?int $assignee = null,
        public readonly ?string $description = null,
        public readonly ?int $technicianCode = null,
        public readonly ?string $resolution = null,
        public readonly ?string $creationDate = null,
        public readonly ?string $creationTime = null,
        public readonly ?string $closingDate = null,
        public readonly ?string $closingTime = null,
        public readonly ?string $dueDate = null,
        public readonly ?string $reminder = null,
        public readonly ?string $reminderPeriod = null,
        public readonly ?string $reminderType = null,
        public readonly ?string $responsibleCode = null,
        public readonly ?string $remarks = null,
    ) {}

    protected static function mapFromArray(array $data): array
    {
        $priority = null;
        if (isset($data['Priority'])) {
            $priority = ServiceCallPriority::tryFrom($data['Priority']);
        }

        return [
            'serviceCallID' => $data['ServiceCallID'] ?? null,
            'subject' => $data['Subject'] ?? null,
            'customerCode' => $data['CustomerCode'] ?? null,
            'customerName' => $data['CustomerName'] ?? null,
            'contactCode' => $data['ContactCode'] ?? null,
            'manufacturerSerialNum' => $data['ManufacturerSerialNum'] ?? null,
            'internalSerialNum' => $data['InternalSerialNum'] ?? null,
            'contractID' => $data['ContractID'] ?? null,
            'itemCode' => $data['ItemCode'] ?? null,
            'itemDescription' => $data['ItemDescription'] ?? null,
            'itemGroup' => $data['ItemGroup'] ?? null,
            'status' => $data['Status'] ?? null,
            'priority' => $priority,
            'callType' => $data['CallType'] ?? null,
            'problemType' => $data['ProblemType'] ?? null,
            'problemSubType' => $data['ProblemSubType'] ?? null,
            'origin' => $data['Origin'] ?? null,
            'assignee' => $data['Assignee'] ?? null,
            'description' => $data['Description'] ?? null,
            'technicianCode' => $data['TechnicianCode'] ?? null,
            'resolution' => $data['Resolution'] ?? null,
            'creationDate' => $data['CreationDate'] ?? null,
            'creationTime' => $data['CreationTime'] ?? null,
            'closingDate' => $data['ClosingDate'] ?? null,
            'closingTime' => $data['ClosingTime'] ?? null,
            'dueDate' => $data['DueDate'] ?? null,
            'reminder' => $data['Reminder'] ?? null,
            'reminderPeriod' => $data['ReminderPeriod'] ?? null,
            'reminderType' => $data['ReminderType'] ?? null,
            'responsibleCode' => $data['ResponsibleCode'] ?? null,
            'remarks' => $data['Remarks'] ?? null,
        ];
    }

    public function toArray(): array
    {
        return array_filter([
            'ServiceCallID' => $this->serviceCallID,
            'Subject' => $this->subject,
            'CustomerCode' => $this->customerCode,
            'CustomerName' => $this->customerName,
            'ContactCode' => $this->contactCode,
            'ManufacturerSerialNum' => $this->manufacturerSerialNum,
            'InternalSerialNum' => $this->internalSerialNum,
            'ContractID' => $this->contractID,
            'ItemCode' => $this->itemCode,
            'ItemDescription' => $this->itemDescription,
            'ItemGroup' => $this->itemGroup,
            'Status' => $this->status,
            'Priority' => $this->priority?->value,
            'CallType' => $this->callType,
            'ProblemType' => $this->problemType,
            'ProblemSubType' => $this->problemSubType,
            'Origin' => $this->origin,
            'Assignee' => $this->assignee,
            'Description' => $this->description,
            'TechnicianCode' => $this->technicianCode,
            'Resolution' => $this->resolution,
            'CreationDate' => $this->creationDate,
            'CreationTime' => $this->creationTime,
            'ClosingDate' => $this->closingDate,
            'ClosingTime' => $this->closingTime,
            'DueDate' => $this->dueDate,
            'Reminder' => $this->reminder,
            'ReminderPeriod' => $this->reminderPeriod,
            'ReminderType' => $this->reminderType,
            'ResponsibleCode' => $this->responsibleCode,
            'Remarks' => $this->remarks,
        ], fn ($value) => $value !== null);
    }
}
