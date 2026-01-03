<?php

declare(strict_types=1);

namespace SapB1\Toolkit\DTOs\Response;

use SapB1\Toolkit\DTOs\Base\BaseDto;

/**
 * @phpstan-consistent-constructor
 */
final class BatchResponseDto extends BaseDto
{
    /**
     * @param  array<BatchItemResponseDto>  $responses
     */
    public function __construct(
        public readonly bool $success = false,
        public readonly array $responses = [],
        public readonly int $successCount = 0,
        public readonly int $errorCount = 0,
    ) {}

    protected static function mapFromArray(array $data): array
    {
        $responses = [];
        $successCount = 0;
        $errorCount = 0;

        if (isset($data['responses']) && is_array($data['responses'])) {
            foreach ($data['responses'] as $response) {
                $item = BatchItemResponseDto::fromArray($response);
                $responses[] = $item;

                if ($item->success) {
                    $successCount++;
                } else {
                    $errorCount++;
                }
            }
        }

        return [
            'success' => $errorCount === 0,
            'responses' => $responses,
            'successCount' => $successCount,
            'errorCount' => $errorCount,
        ];
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return [
            'success' => $this->success,
            'responses' => array_map(
                fn (BatchItemResponseDto $item) => $item->toArray(),
                $this->responses
            ),
            'successCount' => $this->successCount,
            'errorCount' => $this->errorCount,
        ];
    }

    public function hasErrors(): bool
    {
        return $this->errorCount > 0;
    }

    public function isFullSuccess(): bool
    {
        return $this->errorCount === 0;
    }

    /**
     * @return array<BatchItemResponseDto>
     */
    public function getSuccessfulResponses(): array
    {
        return array_filter(
            $this->responses,
            fn (BatchItemResponseDto $item) => $item->success
        );
    }

    /**
     * @return array<BatchItemResponseDto>
     */
    public function getFailedResponses(): array
    {
        return array_filter(
            $this->responses,
            fn (BatchItemResponseDto $item) => ! $item->success
        );
    }
}
