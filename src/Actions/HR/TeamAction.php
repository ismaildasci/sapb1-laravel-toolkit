<?php

declare(strict_types=1);

namespace SapB1\Toolkit\Actions\HR;

use SapB1\Toolkit\Actions\Base\BaseAction;
use SapB1\Toolkit\Builders\HR\TeamBuilder;
use SapB1\Toolkit\DTOs\HR\TeamDto;

/**
 * Team actions.
 */
final class TeamAction extends BaseAction
{
    protected string $entity = 'Teams';

    public function execute(mixed ...$args): mixed
    {
        if (empty($args)) {
            return $this;
        }

        return is_int($args[0]) ? $this->find($args[0]) : $this->create($args[0]);
    }

    public function find(int $teamId): TeamDto
    {
        $data = $this->client()->service($this->entity)->find($teamId);

        return TeamDto::fromResponse($data);
    }

    /**
     * @param  TeamBuilder|array<string, mixed>  $data
     */
    public function create(TeamBuilder|array $data): TeamDto
    {
        $payload = $data instanceof TeamBuilder ? $data->build() : $data;
        $response = $this->client()->service($this->entity)->create($payload);

        return TeamDto::fromResponse($response);
    }

    /**
     * @param  TeamBuilder|array<string, mixed>  $data
     */
    public function update(int $teamId, TeamBuilder|array $data): TeamDto
    {
        $payload = $data instanceof TeamBuilder ? $data->build() : $data;
        $response = $this->client()->service($this->entity)->update($teamId, $payload);

        return TeamDto::fromResponse($response);
    }

    public function delete(int $teamId): bool
    {
        $this->client()->service($this->entity)->delete($teamId);

        return true;
    }

    /**
     * @return array<TeamDto>
     */
    public function all(): array
    {
        $response = $this->client()->service($this->entity)->queryBuilder()->get();

        return array_map(fn (array $item) => TeamDto::fromResponse($item), $response['value'] ?? []);
    }
}
