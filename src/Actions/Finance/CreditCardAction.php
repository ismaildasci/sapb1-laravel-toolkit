<?php

declare(strict_types=1);

namespace SapB1\Toolkit\Actions\Finance;

use SapB1\Toolkit\Actions\Base\BaseAction;
use SapB1\Toolkit\Builders\Finance\CreditCardBuilder;
use SapB1\Toolkit\DTOs\Finance\CreditCardDto;

/**
 * Credit Card actions.
 */
final class CreditCardAction extends BaseAction
{
    protected string $entity = 'CreditCards';

    public function execute(mixed ...$args): mixed
    {
        if (empty($args)) {
            return $this;
        }

        return is_int($args[0]) ? $this->find($args[0]) : $this->create($args[0]);
    }

    public function find(int $creditCardCode): CreditCardDto
    {
        $data = $this->client()->service($this->entity)->find($creditCardCode);

        return CreditCardDto::fromResponse($data);
    }

    /**
     * @param  CreditCardBuilder|array<string, mixed>  $data
     */
    public function create(CreditCardBuilder|array $data): CreditCardDto
    {
        $payload = $data instanceof CreditCardBuilder ? $data->build() : $data;
        $response = $this->client()->service($this->entity)->create($payload);

        return CreditCardDto::fromResponse($response);
    }

    /**
     * @param  CreditCardBuilder|array<string, mixed>  $data
     */
    public function update(int $creditCardCode, CreditCardBuilder|array $data): CreditCardDto
    {
        $payload = $data instanceof CreditCardBuilder ? $data->build() : $data;
        $response = $this->client()->service($this->entity)->update($creditCardCode, $payload);

        return CreditCardDto::fromResponse($response);
    }

    public function delete(int $creditCardCode): bool
    {
        $this->client()->service($this->entity)->delete($creditCardCode);

        return true;
    }

    /**
     * @return array<CreditCardDto>
     */
    public function all(): array
    {
        $response = $this->client()->service($this->entity)->queryBuilder()->get();

        return array_map(fn (array $item) => CreditCardDto::fromResponse($item), $response['value'] ?? []);
    }
}
