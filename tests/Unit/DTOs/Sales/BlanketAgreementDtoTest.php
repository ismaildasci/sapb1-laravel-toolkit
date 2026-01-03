<?php

declare(strict_types=1);

use SapB1\Toolkit\DTOs\Sales\BlanketAgreementDto;
use SapB1\Toolkit\DTOs\Sales\BlanketAgreementItemLineDto;
use SapB1\Toolkit\Enums\BlanketAgreementMethod;
use SapB1\Toolkit\Enums\BlanketAgreementStatus;

it('creates from array', function () {
    $data = [
        'AgreementNo' => 1,
        'BPCode' => 'C001',
        'BPName' => 'Test Customer',
        'StartDate' => '2024-01-01',
        'EndDate' => '2024-12-31',
        'AgreementMethod' => 'bamMonetary',
        'Status' => 'asApproved',
        'BlanketAgreementItemsLines' => [
            ['ItemNo' => 'ITEM001', 'PlannedQuantity' => 100],
        ],
    ];

    $dto = BlanketAgreementDto::fromArray($data);

    expect($dto->agreementNo)->toBe(1);
    expect($dto->bpCode)->toBe('C001');
    expect($dto->agreementMethod)->toBe(BlanketAgreementMethod::Monetary);
    expect($dto->status)->toBe(BlanketAgreementStatus::Approved);
    expect($dto->blanketAgreementItemsLines)->toHaveCount(1);
    expect($dto->blanketAgreementItemsLines[0])->toBeInstanceOf(BlanketAgreementItemLineDto::class);
});

it('converts to array', function () {
    $dto = new BlanketAgreementDto(
        agreementNo: 1,
        bpCode: 'C001',
        agreementMethod: BlanketAgreementMethod::Monetary,
        status: BlanketAgreementStatus::Approved,
    );

    $data = $dto->toArray();

    expect($data['AgreementNo'])->toBe(1);
    expect($data['BPCode'])->toBe('C001');
    expect($data['AgreementMethod'])->toBe('bamMonetary');
    expect($data['Status'])->toBe('asApproved');
});

it('can check if active', function () {
    $approved = new BlanketAgreementDto(status: BlanketAgreementStatus::Approved);
    $onHold = new BlanketAgreementDto(status: BlanketAgreementStatus::OnHold);
    $terminated = new BlanketAgreementDto(status: BlanketAgreementStatus::Terminated);

    expect($approved->isActive())->toBeTrue();
    expect($onHold->isActive())->toBeTrue();
    expect($terminated->isActive())->toBeFalse();
});

it('can check if approved', function () {
    $approved = new BlanketAgreementDto(status: BlanketAgreementStatus::Approved);
    $draft = new BlanketAgreementDto(status: BlanketAgreementStatus::Draft);

    expect($approved->isApproved())->toBeTrue();
    expect($draft->isApproved())->toBeFalse();
});
