<?php

declare(strict_types=1);

namespace SapB1\Toolkit\Actions\Purchase;

use SapB1\Toolkit\Actions\Base\DocumentAction;
use SapB1\Toolkit\Builders\Purchase\PurchaseRequestBuilder;
use SapB1\Toolkit\DTOs\Purchase\PurchaseRequestDto;

/**
 * Purchase Request actions.
 */
final class PurchaseRequestAction extends DocumentAction
{
    protected string $entity = 'PurchaseRequests';

    /**
     * @param  int|PurchaseRequestBuilder|array<string, mixed>  ...$args
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
     * Find a purchase request by DocEntry.
     */
    public function find(int $docEntry): PurchaseRequestDto
    {
        $data = $this->getDocument($docEntry);

        return PurchaseRequestDto::fromResponse($data);
    }

    /**
     * Create a new purchase request.
     *
     * @param  PurchaseRequestBuilder|array<string, mixed>  $data
     */
    public function create(PurchaseRequestBuilder|array $data): PurchaseRequestDto
    {
        $payload = $data instanceof PurchaseRequestBuilder ? $data->build() : $data;
        $response = $this->createDocument($payload);

        return PurchaseRequestDto::fromResponse($response);
    }

    /**
     * Update an existing purchase request.
     *
     * @param  PurchaseRequestBuilder|array<string, mixed>  $data
     */
    public function update(int $docEntry, PurchaseRequestBuilder|array $data): PurchaseRequestDto
    {
        $payload = $data instanceof PurchaseRequestBuilder ? $data->build() : $data;
        $response = $this->updateDocument($docEntry, $payload);

        return PurchaseRequestDto::fromResponse($response);
    }

    /**
     * Close a purchase request.
     */
    public function close(int $docEntry): bool
    {
        return $this->closeDocument($docEntry);
    }

    /**
     * Cancel a purchase request.
     */
    public function cancel(int $docEntry): bool
    {
        return $this->cancelDocument($docEntry);
    }

    /**
     * Get all open purchase requests.
     *
     * @return array<PurchaseRequestDto>
     */
    public function getOpen(): array
    {
        $response = $this->client()
            ->service($this->entity)
            ->queryBuilder()
            ->filter("DocumentStatus eq 'bost_Open'")
            ->get();

        return array_map(
            fn (array $item) => PurchaseRequestDto::fromResponse($item),
            $response['value'] ?? []
        );
    }

    /**
     * Get purchase requests by requester.
     *
     * @return array<PurchaseRequestDto>
     */
    public function getByRequester(int $requesterId): array
    {
        $response = $this->client()
            ->service($this->entity)
            ->queryBuilder()
            ->filter("Requester eq {$requesterId}")
            ->get();

        return array_map(
            fn (array $item) => PurchaseRequestDto::fromResponse($item),
            $response['value'] ?? []
        );
    }

    /**
     * Get purchase requests by department.
     *
     * @return array<PurchaseRequestDto>
     */
    public function getByDepartment(int $departmentId): array
    {
        $response = $this->client()
            ->service($this->entity)
            ->queryBuilder()
            ->filter("RequesterDepartment eq {$departmentId}")
            ->get();

        return array_map(
            fn (array $item) => PurchaseRequestDto::fromResponse($item),
            $response['value'] ?? []
        );
    }
}
