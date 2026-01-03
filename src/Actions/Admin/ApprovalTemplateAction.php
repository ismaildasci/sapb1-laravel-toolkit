<?php

declare(strict_types=1);

namespace SapB1\Toolkit\Actions\Admin;

use SapB1\Toolkit\Actions\Base\BaseAction;
use SapB1\Toolkit\Builders\Admin\ApprovalTemplateBuilder;
use SapB1\Toolkit\DTOs\Admin\ApprovalTemplateDto;
use SapB1\Toolkit\Enums\BoYesNo;

/**
 * Approval Template actions.
 */
final class ApprovalTemplateAction extends BaseAction
{
    protected string $entity = 'ApprovalTemplates';

    public function execute(mixed ...$args): mixed
    {
        if (empty($args)) {
            return $this;
        }

        return is_int($args[0]) ? $this->find($args[0]) : $this->create($args[0]);
    }

    public function find(int $code): ApprovalTemplateDto
    {
        $data = $this->client()->service($this->entity)->find($code);

        return ApprovalTemplateDto::fromResponse($data);
    }

    /**
     * @param  ApprovalTemplateBuilder|array<string, mixed>  $data
     */
    public function create(ApprovalTemplateBuilder|array $data): ApprovalTemplateDto
    {
        $payload = $data instanceof ApprovalTemplateBuilder ? $data->build() : $data;
        $response = $this->client()->service($this->entity)->create($payload);

        return ApprovalTemplateDto::fromResponse($response);
    }

    /**
     * @param  ApprovalTemplateBuilder|array<string, mixed>  $data
     */
    public function update(int $code, ApprovalTemplateBuilder|array $data): ApprovalTemplateDto
    {
        $payload = $data instanceof ApprovalTemplateBuilder ? $data->build() : $data;
        $response = $this->client()->service($this->entity)->update($code, $payload);

        return ApprovalTemplateDto::fromResponse($response);
    }

    public function delete(int $code): bool
    {
        $this->client()->service($this->entity)->delete($code);

        return true;
    }

    /**
     * @return array<ApprovalTemplateDto>
     */
    public function all(): array
    {
        $response = $this->client()->service($this->entity)->queryBuilder()->get();

        return array_map(fn (array $item) => ApprovalTemplateDto::fromResponse($item), $response['value'] ?? []);
    }

    /**
     * Get active templates.
     *
     * @return array<ApprovalTemplateDto>
     */
    public function getActive(): array
    {
        $response = $this->client()
            ->service($this->entity)
            ->queryBuilder()
            ->filter("IsActive eq '".BoYesNo::Yes->value."'")
            ->get();

        return array_map(fn (array $item) => ApprovalTemplateDto::fromResponse($item), $response['value'] ?? []);
    }
}
