<?php

declare(strict_types=1);

namespace SapB1\Toolkit\Exceptions;

/**
 * Exception thrown when a document action fails.
 *
 * This exception is used for Close, Cancel, Reopen and other
 * document-level actions in SAP B1.
 */
class DocumentActionException extends SapB1Exception
{
    /**
     * Create exception for a failed action.
     */
    public static function actionFailed(
        string $endpoint,
        int $docEntry,
        string $action,
        string $error
    ): self {
        return new self(
            message: "Failed to {$action} document {$endpoint}({$docEntry}): {$error}",
            context: [
                'endpoint' => $endpoint,
                'docEntry' => $docEntry,
                'action' => $action,
                'error' => $error,
            ]
        );
    }

    /**
     * Create exception for unsupported action.
     */
    public static function actionNotSupported(string $endpoint, string $action): self
    {
        return new self(
            message: "Action '{$action}' is not supported for endpoint '{$endpoint}'",
            context: [
                'endpoint' => $endpoint,
                'action' => $action,
            ]
        );
    }

    /**
     * Create exception for document not found.
     */
    public static function documentNotFound(string $endpoint, int $docEntry): self
    {
        return new self(
            message: "Document {$endpoint}({$docEntry}) not found",
            context: [
                'endpoint' => $endpoint,
                'docEntry' => $docEntry,
            ]
        );
    }

    /**
     * Create exception for invalid document state.
     */
    public static function invalidState(
        string $endpoint,
        int $docEntry,
        string $action,
        string $currentState
    ): self {
        return new self(
            message: "Cannot {$action} document {$endpoint}({$docEntry}): document is {$currentState}",
            context: [
                'endpoint' => $endpoint,
                'docEntry' => $docEntry,
                'action' => $action,
                'currentState' => $currentState,
            ]
        );
    }
}
