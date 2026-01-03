<?php

declare(strict_types=1);

use SapB1\Toolkit\DTOs\Admin\ApprovalRequestDto;
use SapB1\Toolkit\DTOs\Admin\ApprovalRequestLineDto;
use SapB1\Toolkit\Enums\ApprovalRequestStatusType;
use SapB1\Toolkit\Enums\BoYesNo;

it('creates from array', function () {
    $data = [
        'Code' => 1,
        'ObjectType' => '17',
        'IsDraft' => 'tNO',
        'ObjectEntry' => 100,
        'Status' => 'arstPending',
        'OriginatorID' => 5,
        'ApprovalTemplateID' => 1,
    ];

    $dto = ApprovalRequestDto::fromArray($data);

    expect($dto->code)->toBe(1);
    expect($dto->objectType)->toBe(17);
    expect($dto->isDraft)->toBe(BoYesNo::No);
    expect($dto->objectEntry)->toBe(100);
    expect($dto->status)->toBe(ApprovalRequestStatusType::Pending);
    expect($dto->originatorID)->toBe(5);
});

it('creates from response with lines', function () {
    $response = [
        'Code' => 1,
        'Status' => 'arstPending',
        'ApprovalRequestLines' => [
            [
                'StageCode' => 1,
                'UserID' => 10,
                'Status' => 'Pending',
            ],
        ],
    ];

    $dto = ApprovalRequestDto::fromResponse($response);

    expect($dto->approvalRequestLines)->toHaveCount(1);
    expect($dto->approvalRequestLines[0])->toBeInstanceOf(ApprovalRequestLineDto::class);
    expect($dto->approvalRequestLines[0]->stageCode)->toBe(1);
});

it('can check if pending', function () {
    $pending = new ApprovalRequestDto(status: ApprovalRequestStatusType::Pending);
    $approved = new ApprovalRequestDto(status: ApprovalRequestStatusType::Approved);

    expect($pending->isPending())->toBeTrue();
    expect($approved->isPending())->toBeFalse();
});

it('can check if approved', function () {
    $approved = new ApprovalRequestDto(status: ApprovalRequestStatusType::Approved);
    $generated = new ApprovalRequestDto(status: ApprovalRequestStatusType::Generated);
    $pending = new ApprovalRequestDto(status: ApprovalRequestStatusType::Pending);

    expect($approved->isApproved())->toBeTrue();
    expect($generated->isApproved())->toBeTrue();
    expect($pending->isApproved())->toBeFalse();
});
