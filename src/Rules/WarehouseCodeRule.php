<?php

declare(strict_types=1);

namespace SapB1\Toolkit\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use SapB1\Facades\SapB1;

class WarehouseCodeRule implements ValidationRule
{
    public function __construct(
        private readonly string $connection = 'default'
    ) {}

    /**
     * @param  Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (! is_string($value) || $value === '') {
            $fail('The :attribute must be a valid warehouse code.');

            return;
        }

        try {
            /** @var mixed $client */
            $client = SapB1::connection($this->connection);

            $query = $client->service('Warehouses')
                ->queryBuilder()
                ->select(['WarehouseCode'])
                ->where('WarehouseCode', '=', $value);

            /** @var array{value?: array<int, array<string, mixed>>} $response */
            $response = $query->top(1)->get();

            if (empty($response['value'])) {
                $fail('The :attribute does not exist in SAP Business One.');
            }
        } catch (\Exception) {
            $fail('Unable to validate :attribute against SAP Business One.');
        }
    }
}
