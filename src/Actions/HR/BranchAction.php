<?php

declare(strict_types=1);

namespace SapB1\Toolkit\Actions\HR;

use SapB1\Toolkit\Actions\Base\BaseAction;
use SapB1\Toolkit\Builders\HR\BranchBuilder;
use SapB1\Toolkit\DTOs\HR\BranchDto;

/**
 * Branch actions.
 */
final class BranchAction extends BaseAction
{
    protected string $entity = 'Branches';

    public function execute(mixed ...$args): mixed
    {
        if (empty($args)) {
            return $this;
        }

        return is_int($args[0]) ? $this->find($args[0]) : $this->create($args[0]);
    }

    public function find(int $code): BranchDto
    {
        $data = $this->client()->service($this->entity)->find($code);

        return BranchDto::fromResponse($data);
    }

    /**
     * @param  BranchBuilder|array<string, mixed>  $data
     */
    public function create(BranchBuilder|array $data): BranchDto
    {
        $payload = $data instanceof BranchBuilder ? $data->build() : $data;
        $response = $this->client()->service($this->entity)->create($payload);

        return BranchDto::fromResponse($response);
    }

    /**
     * @param  BranchBuilder|array<string, mixed>  $data
     */
    public function update(int $code, BranchBuilder|array $data): BranchDto
    {
        $payload = $data instanceof BranchBuilder ? $data->build() : $data;
        $response = $this->client()->service($this->entity)->update($code, $payload);

        return BranchDto::fromResponse($response);
    }

    public function delete(int $code): bool
    {
        $this->client()->service($this->entity)->delete($code);

        return true;
    }

    /**
     * @return array<BranchDto>
     */
    public function all(): array
    {
        $response = $this->client()->service($this->entity)->queryBuilder()->get();

        return array_map(fn (array $item) => BranchDto::fromResponse($item), $response['value'] ?? []);
    }
}
