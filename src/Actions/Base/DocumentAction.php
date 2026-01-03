<?php

declare(strict_types=1);

namespace SapB1\Toolkit\Actions\Base;

use SapB1\Toolkit\Concerns\Cancellable;
use SapB1\Toolkit\Concerns\Closable;
use SapB1\Toolkit\Concerns\HasApproval;

abstract class DocumentAction extends BaseAction
{
    use Cancellable;
    use Closable;
    use HasApproval;

    /**
     * Get a document by its entry number.
     *
     * @return array<string, mixed>
     */
    protected function getDocument(int $docEntry): array
    {
        return $this->client()
            ->service($this->entity)
            ->find($docEntry);
    }

    /**
     * Create a new document.
     *
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    protected function createDocument(array $data): array
    {
        return $this->client()
            ->service($this->entity)
            ->create($data);
    }

    /**
     * Update an existing document.
     *
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    protected function updateDocument(int $docEntry, array $data): array
    {
        return $this->client()
            ->service($this->entity)
            ->update($docEntry, $data);
    }

    /**
     * Close a document.
     */
    protected function closeDocument(int $docEntry): bool
    {
        $this->client()
            ->service($this->entity)
            ->action($docEntry, 'Close');

        return true;
    }

    /**
     * Cancel a document.
     */
    protected function cancelDocument(int $docEntry): bool
    {
        $this->client()
            ->service($this->entity)
            ->action($docEntry, 'Cancel');

        return true;
    }

    /**
     * Copy document to another entity type.
     *
     * @param  array<int>|null  $lineFilter
     * @return array<string, mixed>
     */
    protected function copyToDocument(
        int $sourceEntry,
        string $targetEntity,
        int $baseType,
        ?array $lineFilter = null
    ): array {
        $source = $this->getDocument($sourceEntry);

        $data = [
            'CardCode' => $source['CardCode'],
            'DocDate' => date('Y-m-d'),
            'DocumentLines' => $this->mapLinesToBase($source, $baseType, $lineFilter),
        ];

        return $this->client()
            ->service($targetEntity)
            ->create($data);
    }

    /**
     * Map source document lines to base document reference.
     *
     * @param  array<string, mixed>  $source
     * @param  array<int>|null  $lineFilter
     * @return array<int, array<string, mixed>>
     */
    protected function mapLinesToBase(array $source, int $baseType, ?array $lineFilter = null): array
    {
        $lines = [];

        foreach ($source['DocumentLines'] ?? [] as $line) {
            $lineNum = $line['LineNum'] ?? 0;

            if ($lineFilter !== null && ! in_array($lineNum, $lineFilter, true)) {
                continue;
            }

            $lines[] = [
                'BaseType' => $baseType,
                'BaseEntry' => $source['DocEntry'],
                'BaseLine' => $lineNum,
            ];
        }

        return $lines;
    }
}
