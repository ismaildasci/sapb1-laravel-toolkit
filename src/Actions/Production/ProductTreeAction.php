<?php

declare(strict_types=1);

namespace SapB1\Toolkit\Actions\Production;

use SapB1\Toolkit\Actions\Base\BaseAction;
use SapB1\Toolkit\Builders\Production\ProductTreeBuilder;
use SapB1\Toolkit\DTOs\Production\ProductTreeDto;
use SapB1\Toolkit\Enums\ProductTreeType;

/**
 * Product Tree (Bill of Materials) actions.
 */
final class ProductTreeAction extends BaseAction
{
    protected string $entity = 'ProductTrees';

    public function execute(mixed ...$args): mixed
    {
        if (empty($args)) {
            return $this;
        }

        return is_string($args[0]) ? $this->find($args[0]) : $this->create($args[0]);
    }

    public function find(string $treeCode): ProductTreeDto
    {
        $data = $this->client()->service($this->entity)->find($treeCode);

        return ProductTreeDto::fromResponse($data);
    }

    /**
     * @param  ProductTreeBuilder|array<string, mixed>  $data
     */
    public function create(ProductTreeBuilder|array $data): ProductTreeDto
    {
        $payload = $data instanceof ProductTreeBuilder ? $data->build() : $data;
        $response = $this->client()->service($this->entity)->create($payload);

        return ProductTreeDto::fromResponse($response);
    }

    /**
     * @param  ProductTreeBuilder|array<string, mixed>  $data
     */
    public function update(string $treeCode, ProductTreeBuilder|array $data): ProductTreeDto
    {
        $payload = $data instanceof ProductTreeBuilder ? $data->build() : $data;
        $response = $this->client()->service($this->entity)->update($treeCode, $payload);

        return ProductTreeDto::fromResponse($response);
    }

    public function delete(string $treeCode): bool
    {
        $this->client()->service($this->entity)->delete($treeCode);

        return true;
    }

    /**
     * @return array<ProductTreeDto>
     */
    public function all(): array
    {
        $response = $this->client()->service($this->entity)->queryBuilder()->get();

        return array_map(fn (array $item) => ProductTreeDto::fromResponse($item), $response['value'] ?? []);
    }

    /**
     * Get product trees by type.
     *
     * @return array<ProductTreeDto>
     */
    public function getByType(ProductTreeType $type): array
    {
        $response = $this->client()
            ->service($this->entity)
            ->queryBuilder()
            ->filter("TreeType eq '{$type->value}'")
            ->get();

        return array_map(fn (array $item) => ProductTreeDto::fromResponse($item), $response['value'] ?? []);
    }

    /**
     * Get production trees (assembly type).
     *
     * @return array<ProductTreeDto>
     */
    public function getProductionTrees(): array
    {
        return $this->getByType(ProductTreeType::Assembly);
    }

    /**
     * Get template trees.
     *
     * @return array<ProductTreeDto>
     */
    public function getTemplateTrees(): array
    {
        return $this->getByType(ProductTreeType::Template);
    }

    /**
     * Get sales trees.
     *
     * @return array<ProductTreeDto>
     */
    public function getSalesTrees(): array
    {
        return $this->getByType(ProductTreeType::Sales);
    }
}
