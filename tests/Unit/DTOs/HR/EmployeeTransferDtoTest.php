<?php

declare(strict_types=1);

use SapB1\Toolkit\DTOs\HR\EmployeeTransferDto;

it('creates from array', function () {
    $data = [
        'TransferID' => 1,
        'TransferDate' => '2024-03-01',
        'EmployeeID' => 10,
        'FromDepartment' => 1,
        'ToDepartment' => 2,
        'FromBranch' => 1,
        'ToBranch' => 2,
        'Comment' => 'Internal transfer',
    ];

    $dto = EmployeeTransferDto::fromArray($data);

    expect($dto->transferID)->toBe(1);
    expect($dto->transferDate)->toBe('2024-03-01');
    expect($dto->employeeID)->toBe(10);
    expect($dto->fromDepartment)->toBe(1);
    expect($dto->toDepartment)->toBe(2);
    expect($dto->comment)->toBe('Internal transfer');
});

it('converts to array', function () {
    $dto = new EmployeeTransferDto(
        transferID: 1,
        transferDate: '2024-03-01',
        employeeID: 10,
        fromDepartment: 1,
        toDepartment: 2,
    );

    $array = $dto->toArray();

    expect($array['TransferID'])->toBe(1);
    expect($array['TransferDate'])->toBe('2024-03-01');
    expect($array['EmployeeID'])->toBe(10);
    expect($array['FromDepartment'])->toBe(1);
    expect($array['ToDepartment'])->toBe(2);
});
