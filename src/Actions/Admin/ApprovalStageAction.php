<?php

declare(strict_types=1);

namespace SapB1\Toolkit\Actions\Admin;

use SapB1\Toolkit\Actions\Base\BaseAction;
use SapB1\Toolkit\Builders\Admin\ApprovalStageBuilder;
use SapB1\Toolkit\DTOs\Admin\ApprovalStageDto;

/**
 * Approval Stage actions.
 */
final class ApprovalStageAction extends BaseAction
{
    protected string $entity = 'ApprovalStages';

    public function execute(mixed ...$args): mixed
    {
        if (empty($args)) {
            return $this;
        }

        return is_int($args[0]) ? $this->find($args[0]) : $this->create($args[0]);
    }

    public function find(int $code): ApprovalStageDto
    {
        $data = $this->client()->service($this->entity)->find($code);

        return ApprovalStageDto::fromResponse($data);
    }

    /**
     * @param  ApprovalStageBuilder|array<string, mixed>  $data
     */
    public function create(ApprovalStageBuilder|array $data): ApprovalStageDto
    {
        $payload = $data instanceof ApprovalStageBuilder ? $data->build() : $data;
        $response = $this->client()->service($this->entity)->create($payload);

        return ApprovalStageDto::fromResponse($response);
    }

    /**
     * @param  ApprovalStageBuilder|array<string, mixed>  $data
     */
    public function update(int $code, ApprovalStageBuilder|array $data): ApprovalStageDto
    {
        $payload = $data instanceof ApprovalStageBuilder ? $data->build() : $data;
        $response = $this->client()->service($this->entity)->update($code, $payload);

        return ApprovalStageDto::fromResponse($response);
    }

    public function delete(int $code): bool
    {
        $this->client()->service($this->entity)->delete($code);

        return true;
    }

    /**
     * @return array<ApprovalStageDto>
     */
    public function all(): array
    {
        $response = $this->client()->service($this->entity)->queryBuilder()->get();

        return array_map(fn (array $item) => ApprovalStageDto::fromResponse($item), $response['value'] ?? []);
    }
}
