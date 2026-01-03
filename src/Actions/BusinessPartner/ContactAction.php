<?php

declare(strict_types=1);

namespace SapB1\Toolkit\Actions\BusinessPartner;

use SapB1\Toolkit\Actions\Base\BaseAction;
use SapB1\Toolkit\Builders\BusinessPartner\ContactBuilder;
use SapB1\Toolkit\DTOs\BusinessPartner\ContactPersonDto;

/**
 * Contact Employee actions (standalone CRUD for contacts).
 */
final class ContactAction extends BaseAction
{
    protected string $entity = 'ContactEmployees';

    public function execute(mixed ...$args): mixed
    {
        if (empty($args)) {
            return $this;
        }

        return is_int($args[0]) ? $this->find($args[0]) : $this->create($args[0]);
    }

    public function find(int $internalCode): ContactPersonDto
    {
        $data = $this->client()->service($this->entity)->find($internalCode);

        return ContactPersonDto::fromResponse($data);
    }

    /**
     * @param  ContactBuilder|array<string, mixed>  $data
     */
    public function create(ContactBuilder|array $data): ContactPersonDto
    {
        $payload = $data instanceof ContactBuilder ? $data->build() : $data;
        $response = $this->client()->service($this->entity)->create($payload);

        return ContactPersonDto::fromResponse($response);
    }

    /**
     * @param  ContactBuilder|array<string, mixed>  $data
     */
    public function update(int $internalCode, ContactBuilder|array $data): ContactPersonDto
    {
        $payload = $data instanceof ContactBuilder ? $data->build() : $data;
        $response = $this->client()->service($this->entity)->update($internalCode, $payload);

        return ContactPersonDto::fromResponse($response);
    }

    public function delete(int $internalCode): bool
    {
        $this->client()->service($this->entity)->delete($internalCode);

        return true;
    }

    /**
     * @return array<ContactPersonDto>
     */
    public function all(): array
    {
        $response = $this->client()->service($this->entity)->queryBuilder()->get();

        return array_map(fn (array $item) => ContactPersonDto::fromResponse($item), $response['value'] ?? []);
    }

    /**
     * Get contacts by business partner.
     *
     * @return array<ContactPersonDto>
     */
    public function getByBusinessPartner(string $cardCode): array
    {
        $response = $this->client()
            ->service($this->entity)
            ->queryBuilder()
            ->filter("CardCode eq '{$cardCode}'")
            ->get();

        return array_map(fn (array $item) => ContactPersonDto::fromResponse($item), $response['value'] ?? []);
    }

    /**
     * Get active contacts.
     *
     * @return array<ContactPersonDto>
     */
    public function getActive(): array
    {
        $response = $this->client()
            ->service($this->entity)
            ->queryBuilder()
            ->filter("Active eq 'tYES'")
            ->get();

        return array_map(fn (array $item) => ContactPersonDto::fromResponse($item), $response['value'] ?? []);
    }

    /**
     * Search contacts by name.
     *
     * @return array<ContactPersonDto>
     */
    public function search(string $query): array
    {
        $response = $this->client()
            ->service($this->entity)
            ->queryBuilder()
            ->filter("contains(Name, '{$query}')")
            ->get();

        return array_map(fn (array $item) => ContactPersonDto::fromResponse($item), $response['value'] ?? []);
    }
}
