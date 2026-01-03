<?php

declare(strict_types=1);

namespace SapB1\Toolkit\Actions\BusinessPartner;

use SapB1\Toolkit\Actions\Base\BaseAction;
use SapB1\Toolkit\Builders\BusinessPartner\IndustryBuilder;
use SapB1\Toolkit\DTOs\BusinessPartner\IndustryDto;

/**
 * Industry actions.
 */
final class IndustryAction extends BaseAction
{
    protected string $entity = 'Industries';

    public function execute(mixed ...$args): mixed
    {
        if (empty($args)) {
            return $this;
        }

        return is_int($args[0]) ? $this->find($args[0]) : $this->create($args[0]);
    }

    public function find(int $industryCode): IndustryDto
    {
        $data = $this->client()->service($this->entity)->find($industryCode);

        return IndustryDto::fromResponse($data);
    }

    /**
     * @param  IndustryBuilder|array<string, mixed>  $data
     */
    public function create(IndustryBuilder|array $data): IndustryDto
    {
        $payload = $data instanceof IndustryBuilder ? $data->build() : $data;
        $response = $this->client()->service($this->entity)->create($payload);

        return IndustryDto::fromResponse($response);
    }

    /**
     * @param  IndustryBuilder|array<string, mixed>  $data
     */
    public function update(int $industryCode, IndustryBuilder|array $data): IndustryDto
    {
        $payload = $data instanceof IndustryBuilder ? $data->build() : $data;
        $response = $this->client()->service($this->entity)->update($industryCode, $payload);

        return IndustryDto::fromResponse($response);
    }

    public function delete(int $industryCode): bool
    {
        $this->client()->service($this->entity)->delete($industryCode);

        return true;
    }

    /**
     * @return array<IndustryDto>
     */
    public function all(): array
    {
        $response = $this->client()->service($this->entity)->queryBuilder()->get();

        return array_map(fn (array $item) => IndustryDto::fromResponse($item), $response['value'] ?? []);
    }
}
