<?php

declare(strict_types=1);

namespace SapB1\Toolkit\Actions\HR;

use SapB1\Toolkit\Actions\Base\BaseAction;
use SapB1\Toolkit\Builders\HR\TerminationReasonBuilder;
use SapB1\Toolkit\DTOs\HR\TerminationReasonDto;

/**
 * Termination Reason actions.
 */
final class TerminationReasonAction extends BaseAction
{
    protected string $entity = 'TerminationReason';

    public function execute(mixed ...$args): mixed
    {
        if (empty($args)) {
            return $this;
        }

        return is_int($args[0]) ? $this->find($args[0]) : $this->create($args[0]);
    }

    public function find(int $reasonId): TerminationReasonDto
    {
        $data = $this->client()->service($this->entity)->find($reasonId);

        return TerminationReasonDto::fromResponse($data);
    }

    /**
     * @param  TerminationReasonBuilder|array<string, mixed>  $data
     */
    public function create(TerminationReasonBuilder|array $data): TerminationReasonDto
    {
        $payload = $data instanceof TerminationReasonBuilder ? $data->build() : $data;
        $response = $this->client()->service($this->entity)->create($payload);

        return TerminationReasonDto::fromResponse($response);
    }

    /**
     * @param  TerminationReasonBuilder|array<string, mixed>  $data
     */
    public function update(int $reasonId, TerminationReasonBuilder|array $data): TerminationReasonDto
    {
        $payload = $data instanceof TerminationReasonBuilder ? $data->build() : $data;
        $response = $this->client()->service($this->entity)->update($reasonId, $payload);

        return TerminationReasonDto::fromResponse($response);
    }

    public function delete(int $reasonId): bool
    {
        $this->client()->service($this->entity)->delete($reasonId);

        return true;
    }

    /**
     * @return array<TerminationReasonDto>
     */
    public function all(): array
    {
        $response = $this->client()->service($this->entity)->queryBuilder()->get();

        return array_map(fn (array $item) => TerminationReasonDto::fromResponse($item), $response['value'] ?? []);
    }
}
