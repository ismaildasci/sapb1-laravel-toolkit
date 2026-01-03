<?php

declare(strict_types=1);

namespace SapB1\Toolkit\DTOs\Response;

use SapB1\Toolkit\DTOs\Base\BaseDto;

/**
 * @phpstan-consistent-constructor
 */
final class BatchItemResponseDto extends BaseDto
{
    /**
     * @param  array<string, mixed>|null  $data
     */
    public function __construct(
        public readonly bool $success = false,
        public readonly ?string $contentId = null,
        public readonly int $statusCode = 200,
        public readonly ?array $data = null,
        public readonly ?string $errorMessage = null,
        public readonly ?int $errorCode = null,
    ) {}

    protected static function mapFromArray(array $data): array
    {
        $isSuccess = ($data['statusCode'] ?? 200) >= 200 && ($data['statusCode'] ?? 200) < 300;

        return [
            'success' => $isSuccess,
            'contentId' => $data['contentId'] ?? null,
            'statusCode' => (int) ($data['statusCode'] ?? 200),
            'data' => $data['data'] ?? null,
            'errorMessage' => $data['error']['message']['value'] ?? $data['errorMessage'] ?? null,
            'errorCode' => isset($data['error']['code']) ? (int) $data['error']['code'] : null,
        ];
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        $result = [
            'success' => $this->success,
            'statusCode' => $this->statusCode,
        ];

        if ($this->contentId !== null) {
            $result['contentId'] = $this->contentId;
        }

        if ($this->data !== null) {
            $result['data'] = $this->data;
        }

        if ($this->errorMessage !== null) {
            $result['errorMessage'] = $this->errorMessage;
        }

        if ($this->errorCode !== null) {
            $result['errorCode'] = $this->errorCode;
        }

        return $result;
    }
}
