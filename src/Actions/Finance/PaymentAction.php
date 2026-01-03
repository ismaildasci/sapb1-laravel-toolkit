<?php

declare(strict_types=1);

namespace SapB1\Toolkit\Actions\Finance;

use SapB1\Toolkit\Actions\Base\DocumentAction;
use SapB1\Toolkit\Builders\Finance\PaymentBuilder;
use SapB1\Toolkit\DTOs\Finance\PaymentDto;

/**
 * Incoming/Outgoing Payment actions.
 */
final class PaymentAction extends DocumentAction
{
    protected string $entity = 'IncomingPayments';

    protected bool $outgoing = false;

    /**
     * Switch to outgoing payments.
     */
    public function outgoing(): static
    {
        $this->outgoing = true;
        $this->entity = 'VendorPayments';

        return $this;
    }

    /**
     * Switch to incoming payments.
     */
    public function incoming(): static
    {
        $this->outgoing = false;
        $this->entity = 'IncomingPayments';

        return $this;
    }

    /**
     * @param  int|PaymentBuilder|array<string, mixed>  ...$args
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
     * Find a payment by DocEntry.
     */
    public function find(int $docEntry): PaymentDto
    {
        $data = $this->getDocument($docEntry);

        return PaymentDto::fromResponse($data);
    }

    /**
     * Create a new payment.
     *
     * @param  PaymentBuilder|array<string, mixed>  $data
     */
    public function create(PaymentBuilder|array $data): PaymentDto
    {
        $payload = $data instanceof PaymentBuilder ? $data->build() : $data;
        $response = $this->createDocument($payload);

        return PaymentDto::fromResponse($response);
    }

    /**
     * Cancel a payment.
     */
    public function cancel(int $docEntry): bool
    {
        return $this->cancelDocument($docEntry);
    }

    /**
     * Get payments by business partner.
     *
     * @return array<PaymentDto>
     */
    public function getByBusinessPartner(string $cardCode): array
    {
        $response = $this->client()
            ->service($this->entity)
            ->queryBuilder()
            ->filter("CardCode eq '{$cardCode}'")
            ->get();

        return array_map(
            fn (array $item) => PaymentDto::fromResponse($item),
            $response['value'] ?? []
        );
    }

    /**
     * Get payments by date range.
     *
     * @return array<PaymentDto>
     */
    public function getByDateRange(string $fromDate, string $toDate): array
    {
        $response = $this->client()
            ->service($this->entity)
            ->queryBuilder()
            ->filter("DocDate ge '{$fromDate}' and DocDate le '{$toDate}'")
            ->get();

        return array_map(
            fn (array $item) => PaymentDto::fromResponse($item),
            $response['value'] ?? []
        );
    }
}
