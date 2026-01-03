<?php

declare(strict_types=1);

namespace SapB1\Toolkit\Actions\Purchase;

use SapB1\Toolkit\Actions\Base\DocumentAction;
use SapB1\Toolkit\Builders\Purchase\PurchaseDownPaymentBuilder;
use SapB1\Toolkit\DTOs\Purchase\PurchaseDownPaymentDto;
use SapB1\Toolkit\Enums\DocumentType;

/**
 * Purchase Down Payment actions.
 */
final class PurchaseDownPaymentAction extends DocumentAction
{
    protected string $entity = 'PurchaseDownPayments';

    /**
     * @param  int|PurchaseDownPaymentBuilder|array<string, mixed>  ...$args
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
     * Find a purchase down payment by DocEntry.
     */
    public function find(int $docEntry): PurchaseDownPaymentDto
    {
        $data = $this->getDocument($docEntry);

        return PurchaseDownPaymentDto::fromResponse($data);
    }

    /**
     * Create a new purchase down payment.
     *
     * @param  PurchaseDownPaymentBuilder|array<string, mixed>  $data
     */
    public function create(PurchaseDownPaymentBuilder|array $data): PurchaseDownPaymentDto
    {
        $payload = $data instanceof PurchaseDownPaymentBuilder ? $data->build() : $data;
        $response = $this->createDocument($payload);

        return PurchaseDownPaymentDto::fromResponse($response);
    }

    /**
     * Update an existing purchase down payment.
     *
     * @param  PurchaseDownPaymentBuilder|array<string, mixed>  $data
     */
    public function update(int $docEntry, PurchaseDownPaymentBuilder|array $data): PurchaseDownPaymentDto
    {
        $payload = $data instanceof PurchaseDownPaymentBuilder ? $data->build() : $data;
        $response = $this->updateDocument($docEntry, $payload);

        return PurchaseDownPaymentDto::fromResponse($response);
    }

    /**
     * Cancel a purchase down payment.
     */
    public function cancel(int $docEntry): bool
    {
        return $this->cancelDocument($docEntry);
    }

    /**
     * Close a purchase down payment.
     */
    public function close(int $docEntry): bool
    {
        return $this->closeDocument($docEntry);
    }

    /**
     * Copy down payment to purchase invoice.
     *
     * @param  array<int>|null  $lineFilter
     */
    public function copyToPurchaseInvoice(int $docEntry, ?array $lineFilter = null): mixed
    {
        return $this->copyToDocument(
            $docEntry,
            'PurchaseInvoices',
            DocumentType::APDownPayment->value,
            $lineFilter
        );
    }

    /**
     * Get open purchase down payments.
     *
     * @return array<PurchaseDownPaymentDto>
     */
    public function getOpen(): array
    {
        $response = $this->client()
            ->service($this->entity)
            ->queryBuilder()
            ->filter("DocumentStatus eq 'bost_Open'")
            ->get();

        return array_map(
            fn (array $item) => PurchaseDownPaymentDto::fromResponse($item),
            $response['value'] ?? []
        );
    }

    /**
     * Get purchase down payments by vendor.
     *
     * @return array<PurchaseDownPaymentDto>
     */
    public function getByVendor(string $cardCode): array
    {
        $response = $this->client()
            ->service($this->entity)
            ->queryBuilder()
            ->filter("CardCode eq '{$cardCode}'")
            ->get();

        return array_map(
            fn (array $item) => PurchaseDownPaymentDto::fromResponse($item),
            $response['value'] ?? []
        );
    }

    /**
     * Get purchase down payments that can be drawn (applied to invoices).
     *
     * @return array<PurchaseDownPaymentDto>
     */
    public function getDrawable(string $cardCode): array
    {
        $response = $this->client()
            ->service($this->entity)
            ->queryBuilder()
            ->filter("CardCode eq '{$cardCode}' and DocumentStatus eq 'bost_Open'")
            ->get();

        return array_map(
            fn (array $item) => PurchaseDownPaymentDto::fromResponse($item),
            $response['value'] ?? []
        );
    }
}
