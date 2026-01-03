<?php

declare(strict_types=1);

namespace SapB1\Toolkit\Actions\Admin;

use SapB1\Toolkit\Actions\Base\BaseAction;
use SapB1\Toolkit\Builders\Admin\QueryCategoryBuilder;
use SapB1\Toolkit\DTOs\Admin\QueryCategoryDto;

/**
 * Query Category actions.
 */
final class QueryCategoryAction extends BaseAction
{
    protected string $entity = 'QueryCategories';

    public function execute(mixed ...$args): mixed
    {
        if (empty($args)) {
            return $this;
        }

        return is_int($args[0]) ? $this->find($args[0]) : $this->create($args[0]);
    }

    public function find(int $code): QueryCategoryDto
    {
        $data = $this->client()->service($this->entity)->find($code);

        return QueryCategoryDto::fromResponse($data);
    }

    /**
     * @param  QueryCategoryBuilder|array<string, mixed>  $data
     */
    public function create(QueryCategoryBuilder|array $data): QueryCategoryDto
    {
        $payload = $data instanceof QueryCategoryBuilder ? $data->build() : $data;
        $response = $this->client()->service($this->entity)->create($payload);

        return QueryCategoryDto::fromResponse($response);
    }

    /**
     * @param  QueryCategoryBuilder|array<string, mixed>  $data
     */
    public function update(int $code, QueryCategoryBuilder|array $data): QueryCategoryDto
    {
        $payload = $data instanceof QueryCategoryBuilder ? $data->build() : $data;
        $response = $this->client()->service($this->entity)->update($code, $payload);

        return QueryCategoryDto::fromResponse($response);
    }

    public function delete(int $code): bool
    {
        $this->client()->service($this->entity)->delete($code);

        return true;
    }

    /**
     * @return array<QueryCategoryDto>
     */
    public function all(): array
    {
        $response = $this->client()->service($this->entity)->queryBuilder()->get();

        return array_map(fn (array $item) => QueryCategoryDto::fromResponse($item), $response['value'] ?? []);
    }
}
