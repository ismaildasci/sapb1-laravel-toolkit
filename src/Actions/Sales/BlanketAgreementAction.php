<?php

declare(strict_types=1);

namespace SapB1\Toolkit\Actions\Sales;

use SapB1\Toolkit\Actions\Base\BaseAction;
use SapB1\Toolkit\Builders\Sales\BlanketAgreementBuilder;
use SapB1\Toolkit\DTOs\Sales\BlanketAgreementDto;

/**
 * Blanket Agreement actions.
 */
final class BlanketAgreementAction extends BaseAction
{
    protected string $entity = 'BlanketAgreements';

    /**
     * @param  int|BlanketAgreementBuilder|array<string, mixed>  ...$args
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
     * Find a blanket agreement by AgreementNo.
     */
    public function find(int $agreementNo): BlanketAgreementDto
    {
        $response = $this->client()
            ->service($this->entity)
            ->find($agreementNo);

        return BlanketAgreementDto::fromResponse($response);
    }

    /**
     * Create a new blanket agreement.
     *
     * @param  BlanketAgreementBuilder|array<string, mixed>  $data
     */
    public function create(BlanketAgreementBuilder|array $data): BlanketAgreementDto
    {
        $payload = $data instanceof BlanketAgreementBuilder ? $data->build() : $data;
        $response = $this->client()
            ->service($this->entity)
            ->create($payload);

        return BlanketAgreementDto::fromResponse($response);
    }

    /**
     * Update an existing blanket agreement.
     *
     * @param  BlanketAgreementBuilder|array<string, mixed>  $data
     */
    public function update(int $agreementNo, BlanketAgreementBuilder|array $data): BlanketAgreementDto
    {
        $payload = $data instanceof BlanketAgreementBuilder ? $data->build() : $data;
        $response = $this->client()
            ->service($this->entity)
            ->update($agreementNo, $payload);

        return BlanketAgreementDto::fromResponse($response);
    }

    /**
     * Terminate a blanket agreement.
     */
    public function terminate(int $agreementNo, string $terminateDate): bool
    {
        $response = $this->client()
            ->service($this->entity)
            ->action($agreementNo, 'Terminate', ['TerminateDate' => $terminateDate]);

        return $response !== null;
    }

    /**
     * Get all active blanket agreements.
     *
     * @return array<BlanketAgreementDto>
     */
    public function getActive(): array
    {
        $response = $this->client()
            ->service($this->entity)
            ->queryBuilder()
            ->filter("Status eq 'asApproved'")
            ->get();

        return array_map(
            fn (array $item) => BlanketAgreementDto::fromResponse($item),
            $response['value'] ?? []
        );
    }

    /**
     * Get blanket agreements by business partner.
     *
     * @return array<BlanketAgreementDto>
     */
    public function getByBusinessPartner(string $bpCode): array
    {
        $response = $this->client()
            ->service($this->entity)
            ->queryBuilder()
            ->filter("BPCode eq '{$bpCode}'")
            ->get();

        return array_map(
            fn (array $item) => BlanketAgreementDto::fromResponse($item),
            $response['value'] ?? []
        );
    }
}
