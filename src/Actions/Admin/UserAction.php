<?php

declare(strict_types=1);

namespace SapB1\Toolkit\Actions\Admin;

use SapB1\Toolkit\Actions\Base\BaseAction;
use SapB1\Toolkit\Builders\Admin\UserBuilder;
use SapB1\Toolkit\DTOs\Admin\UserDto;
use SapB1\Toolkit\Enums\BoYesNo;

/**
 * User actions.
 */
final class UserAction extends BaseAction
{
    protected string $entity = 'Users';

    public function execute(mixed ...$args): mixed
    {
        if (empty($args)) {
            return $this;
        }

        return is_int($args[0]) ? $this->find($args[0]) : $this->create($args[0]);
    }

    public function find(int $internalKey): UserDto
    {
        $data = $this->client()->service($this->entity)->find($internalKey);

        return UserDto::fromResponse($data);
    }

    /**
     * @param  UserBuilder|array<string, mixed>  $data
     */
    public function create(UserBuilder|array $data): UserDto
    {
        $payload = $data instanceof UserBuilder ? $data->build() : $data;
        $response = $this->client()->service($this->entity)->create($payload);

        return UserDto::fromResponse($response);
    }

    /**
     * @param  UserBuilder|array<string, mixed>  $data
     */
    public function update(int $internalKey, UserBuilder|array $data): UserDto
    {
        $payload = $data instanceof UserBuilder ? $data->build() : $data;
        $response = $this->client()->service($this->entity)->update($internalKey, $payload);

        return UserDto::fromResponse($response);
    }

    public function delete(int $internalKey): bool
    {
        $this->client()->service($this->entity)->delete($internalKey);

        return true;
    }

    /**
     * @return array<UserDto>
     */
    public function all(): array
    {
        $response = $this->client()->service($this->entity)->queryBuilder()->get();

        return array_map(fn (array $item) => UserDto::fromResponse($item), $response['value'] ?? []);
    }

    /**
     * Get active (not locked) users.
     *
     * @return array<UserDto>
     */
    public function getActive(): array
    {
        $response = $this->client()
            ->service($this->entity)
            ->queryBuilder()
            ->filter("Locked eq '".BoYesNo::No->value."'")
            ->get();

        return array_map(fn (array $item) => UserDto::fromResponse($item), $response['value'] ?? []);
    }

    /**
     * Get superusers.
     *
     * @return array<UserDto>
     */
    public function getSuperusers(): array
    {
        $response = $this->client()
            ->service($this->entity)
            ->queryBuilder()
            ->filter("Superuser eq '".BoYesNo::Yes->value."'")
            ->get();

        return array_map(fn (array $item) => UserDto::fromResponse($item), $response['value'] ?? []);
    }

    /**
     * Get current user.
     */
    public function getCurrentUser(): UserDto
    {
        $data = $this->client()->post('UsersService_GetCurrentUser', []);

        return UserDto::fromResponse($data);
    }
}
