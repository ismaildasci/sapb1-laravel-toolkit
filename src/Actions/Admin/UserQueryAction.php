<?php

declare(strict_types=1);

namespace SapB1\Toolkit\Actions\Admin;

use SapB1\Toolkit\Actions\Base\BaseAction;
use SapB1\Toolkit\Builders\Admin\UserQueryBuilder;
use SapB1\Toolkit\DTOs\Admin\UserQueryDto;

/**
 * User Query actions.
 */
final class UserQueryAction extends BaseAction
{
    protected string $entity = 'UserQueries';

    public function execute(mixed ...$args): mixed
    {
        if (empty($args)) {
            return $this;
        }

        return is_int($args[0]) ? $this->find($args[0]) : $this->create($args[0]);
    }

    public function find(int $internalKey): UserQueryDto
    {
        $data = $this->client()->service($this->entity)->find($internalKey);

        return UserQueryDto::fromResponse($data);
    }

    /**
     * @param  UserQueryBuilder|array<string, mixed>  $data
     */
    public function create(UserQueryBuilder|array $data): UserQueryDto
    {
        $payload = $data instanceof UserQueryBuilder ? $data->build() : $data;
        $response = $this->client()->service($this->entity)->create($payload);

        return UserQueryDto::fromResponse($response);
    }

    /**
     * @param  UserQueryBuilder|array<string, mixed>  $data
     */
    public function update(int $internalKey, UserQueryBuilder|array $data): UserQueryDto
    {
        $payload = $data instanceof UserQueryBuilder ? $data->build() : $data;
        $response = $this->client()->service($this->entity)->update($internalKey, $payload);

        return UserQueryDto::fromResponse($response);
    }

    public function delete(int $internalKey): bool
    {
        $this->client()->service($this->entity)->delete($internalKey);

        return true;
    }

    /**
     * @return array<UserQueryDto>
     */
    public function all(): array
    {
        $response = $this->client()->service($this->entity)->queryBuilder()->get();

        return array_map(fn (array $item) => UserQueryDto::fromResponse($item), $response['value'] ?? []);
    }

    /**
     * Get queries by category.
     *
     * @return array<UserQueryDto>
     */
    public function getByCategory(int $categoryCode): array
    {
        $response = $this->client()
            ->service($this->entity)
            ->queryBuilder()
            ->filter("QueryCategory eq {$categoryCode}")
            ->get();

        return array_map(fn (array $item) => UserQueryDto::fromResponse($item), $response['value'] ?? []);
    }
}
