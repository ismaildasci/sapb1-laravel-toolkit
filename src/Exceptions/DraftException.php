<?php

declare(strict_types=1);

namespace SapB1\Toolkit\Exceptions;

/**
 * Exception thrown when draft operations fail.
 *
 * This exception is used for Create, Update, Delete, and SaveAsDocument
 * operations on the Drafts endpoint in SAP B1.
 */
class DraftException extends SapB1Exception
{
    /**
     * Create exception for failed draft creation.
     */
    public static function createFailed(string $documentType, string $error): self
    {
        return new self(
            message: "Failed to create {$documentType} draft: {$error}",
            context: [
                'documentType' => $documentType,
                'error' => $error,
            ]
        );
    }

    /**
     * Create exception for draft not found.
     */
    public static function notFound(int $docEntry): self
    {
        return new self(
            message: "Draft with DocEntry {$docEntry} not found",
            context: [
                'docEntry' => $docEntry,
            ]
        );
    }

    /**
     * Create exception for failed draft update.
     */
    public static function updateFailed(int $docEntry, string $error): self
    {
        return new self(
            message: "Failed to update draft {$docEntry}: {$error}",
            context: [
                'docEntry' => $docEntry,
                'error' => $error,
            ]
        );
    }

    /**
     * Create exception for failed draft deletion.
     */
    public static function deleteFailed(int $docEntry, string $error): self
    {
        return new self(
            message: "Failed to delete draft {$docEntry}: {$error}",
            context: [
                'docEntry' => $docEntry,
                'error' => $error,
            ]
        );
    }

    /**
     * Create exception for failed save as document.
     */
    public static function saveFailed(int $docEntry, string $error): self
    {
        return new self(
            message: "Failed to save draft {$docEntry} as document: {$error}",
            context: [
                'docEntry' => $docEntry,
                'error' => $error,
            ]
        );
    }
}
