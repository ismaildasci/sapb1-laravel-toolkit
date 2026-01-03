<?php

declare(strict_types=1);

namespace SapB1\Toolkit\Actions\Admin;

use SapB1\Toolkit\Actions\Base\BaseAction;
use SapB1\Toolkit\Builders\Admin\UserLanguageBuilder;
use SapB1\Toolkit\DTOs\Admin\UserLanguageDto;

/**
 * User Language actions.
 */
final class UserLanguageAction extends BaseAction
{
    protected string $entity = 'UserLanguages';

    public function execute(mixed ...$args): mixed
    {
        if (empty($args)) {
            return $this;
        }

        return is_int($args[0]) ? $this->find($args[0]) : $this->create($args[0]);
    }

    public function find(int $code): UserLanguageDto
    {
        $data = $this->client()->service($this->entity)->find($code);

        return UserLanguageDto::fromResponse($data);
    }

    /**
     * @param  UserLanguageBuilder|array<string, mixed>  $data
     */
    public function create(UserLanguageBuilder|array $data): UserLanguageDto
    {
        $payload = $data instanceof UserLanguageBuilder ? $data->build() : $data;
        $response = $this->client()->service($this->entity)->create($payload);

        return UserLanguageDto::fromResponse($response);
    }

    /**
     * @param  UserLanguageBuilder|array<string, mixed>  $data
     */
    public function update(int $code, UserLanguageBuilder|array $data): UserLanguageDto
    {
        $payload = $data instanceof UserLanguageBuilder ? $data->build() : $data;
        $response = $this->client()->service($this->entity)->update($code, $payload);

        return UserLanguageDto::fromResponse($response);
    }

    public function delete(int $code): bool
    {
        $this->client()->service($this->entity)->delete($code);

        return true;
    }

    /**
     * @return array<UserLanguageDto>
     */
    public function all(): array
    {
        $response = $this->client()->service($this->entity)->queryBuilder()->get();

        return array_map(fn (array $item) => UserLanguageDto::fromResponse($item), $response['value'] ?? []);
    }
}
