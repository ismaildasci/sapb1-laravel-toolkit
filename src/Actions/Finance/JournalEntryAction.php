<?php

declare(strict_types=1);

namespace SapB1\Toolkit\Actions\Finance;

use SapB1\Toolkit\Actions\Base\BaseAction;
use SapB1\Toolkit\Builders\Finance\JournalEntryBuilder;
use SapB1\Toolkit\DTOs\Finance\JournalEntryDto;

/**
 * Journal Entry actions.
 */
final class JournalEntryAction extends BaseAction
{
    protected string $entity = 'JournalEntries';

    /**
     * @param  int|JournalEntryBuilder|array<string, mixed>  ...$args
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
     * Find a journal entry by JdtNum.
     */
    public function find(int $jdtNum): JournalEntryDto
    {
        $data = $this->client()
            ->service($this->entity)
            ->find($jdtNum);

        return JournalEntryDto::fromResponse($data);
    }

    /**
     * Create a new journal entry.
     *
     * @param  JournalEntryBuilder|array<string, mixed>  $data
     */
    public function create(JournalEntryBuilder|array $data): JournalEntryDto
    {
        $payload = $data instanceof JournalEntryBuilder ? $data->build() : $data;
        $response = $this->client()
            ->service($this->entity)
            ->create($payload);

        return JournalEntryDto::fromResponse($response);
    }

    /**
     * Cancel a journal entry.
     */
    public function cancel(int $jdtNum): bool
    {
        $this->client()
            ->service($this->entity)
            ->action($jdtNum, 'Cancel');

        return true;
    }

    /**
     * Get journal entries by date range.
     *
     * @return array<JournalEntryDto>
     */
    public function getByDateRange(string $fromDate, string $toDate): array
    {
        $response = $this->client()
            ->service($this->entity)
            ->queryBuilder()
            ->filter("ReferenceDate ge '{$fromDate}' and ReferenceDate le '{$toDate}'")
            ->get();

        return array_map(
            fn (array $item) => JournalEntryDto::fromResponse($item),
            $response['value'] ?? []
        );
    }

    /**
     * Get journal entries by reference.
     *
     * @return array<JournalEntryDto>
     */
    public function getByReference(string $reference): array
    {
        $response = $this->client()
            ->service($this->entity)
            ->queryBuilder()
            ->filter("Reference eq '{$reference}'")
            ->get();

        return array_map(
            fn (array $item) => JournalEntryDto::fromResponse($item),
            $response['value'] ?? []
        );
    }
}
