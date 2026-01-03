<?php

declare(strict_types=1);

namespace SapB1\Toolkit\DTOs\Purchase;

use SapB1\Toolkit\DTOs\DocumentDto;

/**
 * Purchase Request DTO.
 *
 * @phpstan-consistent-constructor
 */
final class PurchaseRequestDto extends DocumentDto
{
    public function __construct(
        ?int $docEntry = null,
        ?int $docNum = null,
        ?string $cardCode = null,
        ?string $cardName = null,
        ?string $docDate = null,
        ?string $docDueDate = null,
        ?string $taxDate = null,
        ?string $docCurrency = null,
        ?float $docRate = null,
        ?float $docTotal = null,
        ?float $docTotalFc = null,
        ?float $vatSum = null,
        ?float $vatSumFc = null,
        ?float $discountPercent = null,
        ?float $totalDiscount = null,
        ?string $numAtCard = null,
        ?string $comments = null,
        ?\SapB1\Toolkit\Enums\DocumentStatus $documentStatus = null,
        ?string $payToCode = null,
        ?string $shipToCode = null,
        ?string $salesPersonCode = null,
        ?string $contactPersonCode = null,
        ?string $series = null,
        ?string $indicator = null,
        ?string $federalTaxId = null,
        ?string $project = null,
        array $documentLines = [],
        public readonly ?int $requester = null,
        public readonly ?string $requesterName = null,
        public readonly ?string $requesterEmail = null,
        public readonly ?int $requesterBranch = null,
        public readonly ?int $requesterDepartment = null,
    ) {
        parent::__construct(
            docEntry: $docEntry,
            docNum: $docNum,
            cardCode: $cardCode,
            cardName: $cardName,
            docDate: $docDate,
            docDueDate: $docDueDate,
            taxDate: $taxDate,
            docCurrency: $docCurrency,
            docRate: $docRate,
            docTotal: $docTotal,
            docTotalFc: $docTotalFc,
            vatSum: $vatSum,
            vatSumFc: $vatSumFc,
            discountPercent: $discountPercent,
            totalDiscount: $totalDiscount,
            numAtCard: $numAtCard,
            comments: $comments,
            documentStatus: $documentStatus,
            payToCode: $payToCode,
            shipToCode: $shipToCode,
            salesPersonCode: $salesPersonCode,
            contactPersonCode: $contactPersonCode,
            series: $series,
            indicator: $indicator,
            federalTaxId: $federalTaxId,
            project: $project,
            documentLines: $documentLines,
        );
    }

    protected static function mapFromArray(array $data): array
    {
        return array_merge(parent::mapFromArray($data), [
            'requester' => $data['Requester'] ?? null,
            'requesterName' => $data['RequesterName'] ?? null,
            'requesterEmail' => $data['RequesterEmail'] ?? null,
            'requesterBranch' => $data['RequesterBranch'] ?? null,
            'requesterDepartment' => $data['RequesterDepartment'] ?? null,
        ]);
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        $data = parent::toArray();

        if ($this->requester !== null) {
            $data['Requester'] = $this->requester;
        }

        if ($this->requesterName !== null) {
            $data['RequesterName'] = $this->requesterName;
        }

        if ($this->requesterEmail !== null) {
            $data['RequesterEmail'] = $this->requesterEmail;
        }

        if ($this->requesterBranch !== null) {
            $data['RequesterBranch'] = $this->requesterBranch;
        }

        if ($this->requesterDepartment !== null) {
            $data['RequesterDepartment'] = $this->requesterDepartment;
        }

        return $data;
    }
}
