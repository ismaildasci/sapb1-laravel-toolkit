<?php

declare(strict_types=1);

namespace SapB1\Toolkit\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use SapB1\Facades\SapB1;

class DocEntryRule implements ValidationRule
{
    public function __construct(
        private readonly string $entity,
        private readonly string $connection = 'default'
    ) {}

    /**
     * @param  Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (! is_numeric($value)) {
            $fail('The :attribute must be a valid document entry number.');

            return;
        }

        try {
            /** @var mixed $client */
            $client = SapB1::connection($this->connection);

            /** @var array<string, mixed> $response */
            $response = $client->service($this->entity)->find((int) $value);

            if (empty($response) || ! isset($response['DocEntry'])) {
                $fail('The :attribute does not exist in SAP Business One.');
            }
        } catch (\Exception) {
            $fail('The :attribute does not exist in SAP Business One.');
        }
    }
}
