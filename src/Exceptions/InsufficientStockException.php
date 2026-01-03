<?php

declare(strict_types=1);

namespace SapB1\Toolkit\Exceptions;

class InsufficientStockException extends SapB1Exception
{
    public function __construct(
        public readonly string $itemCode,
        public readonly float $requested,
        public readonly float $available,
        public readonly ?string $warehouseCode = null,
        ?\Throwable $previous = null
    ) {
        $location = $warehouseCode ? " in warehouse {$warehouseCode}" : '';
        parent::__construct(
            "Insufficient stock for item {$itemCode}{$location}: requested {$requested}, available {$available}",
            400,
            [
                'itemCode' => $itemCode,
                'requested' => $requested,
                'available' => $available,
                'warehouseCode' => $warehouseCode,
            ],
            $previous
        );
    }
}
