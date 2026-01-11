<?php

declare(strict_types=1);

namespace SapB1\Toolkit\Exceptions;

/**
 * Exception thrown when UDF operations fail.
 *
 * This exception is used for User Defined Field (UDF) related errors
 * such as unmapped entities or invalid field access.
 */
class UdfException extends SapB1Exception
{
    /**
     * Create exception for unmapped entity.
     */
    public static function entityNotMapped(string $entity): self
    {
        return new self(
            message: "Entity '{$entity}' is not mapped to a SAP table. Use getFieldsForTable() with the table name directly.",
            context: [
                'entity' => $entity,
            ]
        );
    }

    /**
     * Create exception for unmapped line table.
     */
    public static function lineTableNotMapped(string $entity): self
    {
        return new self(
            message: "Entity '{$entity}' does not have a mapped line table.",
            context: [
                'entity' => $entity,
            ]
        );
    }

    /**
     * Create exception for field not found.
     */
    public static function fieldNotFound(string $entity, string $fieldName): self
    {
        return new self(
            message: "UDF '{$fieldName}' not found for entity '{$entity}'",
            context: [
                'entity' => $entity,
                'fieldName' => $fieldName,
            ]
        );
    }

    /**
     * Create exception for invalid field name.
     */
    public static function invalidFieldName(string $fieldName): self
    {
        return new self(
            message: "Invalid UDF name '{$fieldName}'. UDF names must be alphanumeric and start with a letter.",
            context: [
                'fieldName' => $fieldName,
            ]
        );
    }

    /**
     * Create exception for query failure.
     */
    public static function queryFailed(string $tableName, string $error): self
    {
        return new self(
            message: "Failed to query UDFs for table '{$tableName}': {$error}",
            context: [
                'tableName' => $tableName,
                'error' => $error,
            ]
        );
    }
}
