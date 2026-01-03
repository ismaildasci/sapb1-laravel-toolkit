<?php

declare(strict_types=1);

namespace SapB1\Toolkit\Actions\Sales;

use SapB1\Toolkit\Actions\Base\DocumentAction;
use SapB1\Toolkit\Builders\Sales\DownPaymentBuilder;
use SapB1\Toolkit\DTOs\Sales\DownPaymentDto;
use SapB1\Toolkit\Enums\DocumentType;

/**
 * Sales Down Payment actions.
 */
final class DownPaymentAction extends DocumentAction
{
    protected string $entity = 'DownPayments';

    /**
     * @param  int|DownPaymentBuilder|array<string, mixed>  ...$args
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
     * Find a down payment by DocEntry.
     */
    public function find(int $docEntry): DownPaymentDto
    {
        $data = $this->getDocument($docEntry);

        return DownPaymentDto::fromResponse($data);
    }

    /**
     * Create a new down payment.
     *
     * @param  DownPaymentBuilder|array<string, mixed>  $data
     */
    public function create(DownPaymentBuilder|array $data): DownPaymentDto
    {
        $payload = $data instanceof DownPaymentBuilder ? $data->build() : $data;
        $response = $this->createDocument($payload);

        return DownPaymentDto::fromResponse($response);
    }

    /**
     * Update an existing down payment.
     *
     * @param  DownPaymentBuilder|array<string, mixed>  $data
     */
    public function update(int $docEntry, DownPaymentBuilder|array $data): DownPaymentDto
    {
        $payload = $data instanceof DownPaymentBuilder ? $data->build() : $data;
        $response = $this->updateDocument($docEntry, $payload);

        return DownPaymentDto::fromResponse($response);
    }

    /**
     * Cancel a down payment.
     */
    public function cancel(int $docEntry): bool
    {
        return $this->cancelDocument($docEntry);
    }

    /**
     * Close a down payment.
     */
    public function close(int $docEntry): bool
    {
        return $this->closeDocument($docEntry);
    }

    /**
     * Copy down payment to invoice.
     *
     * @param  array<int>|null  $lineFilter
     */
    public function copyToInvoice(int $docEntry, ?array $lineFilter = null): mixed
    {
        return $this->copyToDocument(
            $docEntry,
            'Invoices',
            DocumentType::ARDownPayment->value,
            $lineFilter
        );
    }

    /**
     * Get open down payments.
     *
     * @return array<DownPaymentDto>
     */
    public function getOpen(): array
    {
        $response = $this->client()
            ->service($this->entity)
            ->queryBuilder()
            ->filter("DocumentStatus eq 'bost_Open'")
            ->get();

        return array_map(
            fn (array $item) => DownPaymentDto::fromResponse($item),
            $response['value'] ?? []
        );
    }

    /**
     * Get down payments by customer.
     *
     * @return array<DownPaymentDto>
     */
    public function getByCustomer(string $cardCode): array
    {
        $response = $this->client()
            ->service($this->entity)
            ->queryBuilder()
            ->filter("CardCode eq '{$cardCode}'")
            ->get();

        return array_map(
            fn (array $item) => DownPaymentDto::fromResponse($item),
            $response['value'] ?? []
        );
    }

    /**
     * Get down payments that can be drawn (applied to invoices).
     *
     * @return array<DownPaymentDto>
     */
    public function getDrawable(string $cardCode): array
    {
        $response = $this->client()
            ->service($this->entity)
            ->queryBuilder()
            ->filter("CardCode eq '{$cardCode}' and DocumentStatus eq 'bost_Open'")
            ->get();

        return array_map(
            fn (array $item) => DownPaymentDto::fromResponse($item),
            $response['value'] ?? []
        );
    }
}
