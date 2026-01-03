<?php

declare(strict_types=1);

use SapB1\Toolkit\Builders\Purchase\PurchaseRequestBuilder;

it('builds purchase request data', function () {
    $builder = PurchaseRequestBuilder::create()
        ->docDate('2024-01-15')
        ->requester(5)
        ->requesterName('John Doe')
        ->requesterEmail('john@example.com')
        ->requesterBranch(1)
        ->requesterDepartment(2);

    $data = $builder->build();

    expect($data['DocDate'])->toBe('2024-01-15');
    expect($data['Requester'])->toBe(5);
    expect($data['RequesterName'])->toBe('John Doe');
    expect($data['RequesterEmail'])->toBe('john@example.com');
    expect($data['RequesterBranch'])->toBe(1);
    expect($data['RequesterDepartment'])->toBe(2);
});

it('can add document lines', function () {
    $builder = PurchaseRequestBuilder::create()
        ->requester(5)
        ->addLine(['ItemCode' => 'ITEM001', 'Quantity' => 10]);

    $data = $builder->build();

    expect($data['DocumentLines'])->toHaveCount(1);
});

it('can be reset', function () {
    $builder = PurchaseRequestBuilder::create()
        ->requester(5)
        ->requesterName('John Doe');

    $builder->reset();
    $data = $builder->build();

    expect($data)->toBeEmpty();
});
