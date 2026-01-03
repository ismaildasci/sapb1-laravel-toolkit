<?php

declare(strict_types=1);

namespace SapB1\Toolkit\Actions\Sales;

use SapB1\Toolkit\Actions\Base\DocumentAction;
use SapB1\Toolkit\Builders\Sales\ReturnBuilder;
use SapB1\Toolkit\DTOs\Sales\ReturnDto;
use SapB1\Toolkit\Enums\DocumentType;

/**
 * Sales Return actions.
 */
final class ReturnAction extends DocumentAction
{
    protected string $entity = 'Returns';

    /**
     * @param  int|ReturnBuilder|array<string, mixed>  ...$args
     */
    public function execute(mixed ...$args): mixed
    {
        if (empty($args)) {
            return $this;
        }

        $first = $args[0];

        if (is_int($first)) {
            return $this->find($first);
        }

        return $this->create($first);
    }

    /**
     * Find a return by DocEntry.
     */
    public function find(int $docEntry): ReturnDto
    {
        $data = $this->getDocument($docEntry);

        return ReturnDto::fromResponse($data);
    }

    /**
     * Create a new return.
     *
     * @param  ReturnBuilder|array<string, mixed>  $data
     */
    public function create(ReturnBuilder|array $data): ReturnDto
    {
        $payload = $data instanceof ReturnBuilder ? $data->build() : $data;
        $response = $this->createDocument($payload);

        return ReturnDto::fromResponse($response);
    }

    /**
     * Update an existing return.
     *
     * @param  ReturnBuilder|array<string, mixed>  $data
     */
    public function update(int $docEntry, ReturnBuilder|array $data): ReturnDto
    {
        $payload = $data instanceof ReturnBuilder ? $data->build() : $data;
        $response = $this->updateDocument($docEntry, $payload);

        return ReturnDto::fromResponse($response);
    }

    /**
     * Cancel a return.
     */
    public function cancel(int $docEntry): bool
    {
        return $this->cancelDocument($docEntry);
    }

    /**
     * Copy return to credit note.
     *
     * @param  array<int>|null  $lineFilter
     */
    public function copyToCreditNote(int $docEntry, ?array $lineFilter = null): mixed
    {
        return $this->copyToDocument(
            $docEntry,
            'CreditNotes',
            DocumentType::Return->value,
            $lineFilter
        );
    }
}
