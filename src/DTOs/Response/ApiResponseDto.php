<?php

declare(strict_types=1);

namespace SapB1\Toolkit\DTOs\Response;

use SapB1\Toolkit\DTOs\Base\BaseDto;

/**
 * @template T
 *
 * @phpstan-consistent-constructor
 */
final class ApiResponseDto extends BaseDto
{
    /**
     * @param  T|null  $data
     * @param  array<string, mixed>  $meta
     */
    public function __construct(
        public readonly bool $success = false,
        public readonly mixed $data = null,
        public readonly ?string $message = null,
        public readonly ?string $errorCode = null,
        public readonly array $meta = [],
    ) {}

    protected static function mapFromArray(array $data): array
    {
        return [
            'success' => $data['success'] ?? false,
            'data' => $data['data'] ?? null,
            'message' => $data['message'] ?? null,
            'errorCode' => $data['errorCode'] ?? null,
            'meta' => $data['meta'] ?? [],
        ];
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        $result = [
            'success' => $this->success,
        ];

        if ($this->data !== null) {
            $result['data'] = $this->data;
        }

        if ($this->message !== null) {
            $result['message'] = $this->message;
        }

        if ($this->errorCode !== null) {
            $result['errorCode'] = $this->errorCode;
        }

        if (! empty($this->meta)) {
            $result['meta'] = $this->meta;
        }

        return $result;
    }

    /**
     * @template TData
     *
     * @param  TData  $data
     * @return self<TData>
     */
    public static function success(mixed $data, ?string $message = null): self
    {
        return new self(
            success: true,
            data: $data,
            message: $message,
        );
    }

    /**
     * @return self<null>
     */
    public static function error(string $message, ?string $errorCode = null): self
    {
        return new self(
            success: false,
            data: null,
            message: $message,
            errorCode: $errorCode,
        );
    }

    public function isSuccess(): bool
    {
        return $this->success;
    }

    public function isError(): bool
    {
        return ! $this->success;
    }
}
