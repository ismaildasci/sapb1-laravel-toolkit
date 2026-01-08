<?php

declare(strict_types=1);

namespace SapB1\Toolkit\Models\Concerns;

use Illuminate\Http\UploadedFile;
use SapB1\Exceptions\AttachmentException;
use SapB1\Facades\SapB1;

/**
 * Trait for models that support file attachments.
 *
 * Provides methods for uploading, downloading, listing, and managing
 * file attachments for SAP B1 entities. Uses the SDK's AttachmentsManager
 * which interacts with the Attachments2 endpoint.
 *
 * @example
 * ```php
 * $order = Order::find(123);
 *
 * // Upload attachment
 * $attachment = $order->addAttachment($file);
 * $attachment = $order->addAttachment('/path/to/file.pdf', 'custom-name.pdf');
 *
 * // List attachments
 * $attachments = $order->attachments();
 *
 * // Download attachment
 * $content = $order->downloadAttachment(0); // First attachment
 *
 * // Check if has attachments
 * if ($order->hasAttachments()) { ... }
 * ```
 */
trait HasAttachments
{
    /**
     * Upload a file attachment to this entity.
     *
     * @param  UploadedFile|string  $file  File or path to file
     * @param  string|null  $fileName  Optional custom filename
     * @return array{AbsoluteEntry: int, FileName: string}
     *
     * @throws AttachmentException
     */
    public function addAttachment(UploadedFile|string $file, ?string $fileName = null): array
    {
        $key = $this->getAttachmentKey();
        $endpoint = $this->getAttachmentEndpoint();

        return $this->getAttachmentsManager()->upload($endpoint, $key, $file, $fileName);
    }

    /**
     * Get all attachments for this entity.
     *
     * @return array<int, array<string, mixed>>
     *
     * @throws AttachmentException
     */
    public function attachments(): array
    {
        $key = $this->getAttachmentKey();
        $endpoint = $this->getAttachmentEndpoint();

        return $this->getAttachmentsManager()->list($endpoint, $key);
    }

    /**
     * Download an attachment by index.
     *
     * @param  int  $index  The index of the attachment in the list
     * @return string The file content
     *
     * @throws AttachmentException
     */
    public function downloadAttachment(int $index = 0): string
    {
        $attachments = $this->attachments();

        if (! isset($attachments[$index])) {
            throw new AttachmentException("Attachment at index {$index} not found");
        }

        // Get the attachment entry from entity
        $attachmentEntry = $this->getAttachmentEntry();

        if ($attachmentEntry === null || $attachmentEntry === -1) {
            throw new AttachmentException('Entity has no attachment entry');
        }

        return $this->getAttachmentsManager()->download($attachmentEntry);
    }

    /**
     * Download an attachment to a directory.
     *
     * @param  int  $index  The index of the attachment in the list
     * @param  string  $savePath  Directory to save the file
     * @return string The file content
     *
     * @throws AttachmentException
     */
    public function downloadAttachmentTo(int $index, string $savePath): string
    {
        $attachments = $this->attachments();

        if (! isset($attachments[$index])) {
            throw new AttachmentException("Attachment at index {$index} not found");
        }

        $attachmentEntry = $this->getAttachmentEntry();

        if ($attachmentEntry === null || $attachmentEntry === -1) {
            throw new AttachmentException('Entity has no attachment entry');
        }

        return $this->getAttachmentsManager()->download($attachmentEntry, $savePath);
    }

    /**
     * Check if this entity has any attachments.
     */
    public function hasAttachments(): bool
    {
        try {
            $attachments = $this->attachments();

            return count($attachments) > 0;
        } catch (AttachmentException) {
            return false;
        }
    }

    /**
     * Get the count of attachments for this entity.
     */
    public function attachmentCount(): int
    {
        try {
            return count($this->attachments());
        } catch (AttachmentException) {
            return 0;
        }
    }

    /**
     * Get attachment metadata for this entity.
     *
     * @return array<string, mixed>|null
     */
    public function attachmentMetadata(): ?array
    {
        $attachmentEntry = $this->getAttachmentEntry();

        if ($attachmentEntry === null || $attachmentEntry === -1) {
            return null;
        }

        try {
            return $this->getAttachmentsManager()->metadata($attachmentEntry);
        } catch (AttachmentException) {
            return null;
        }
    }

    /**
     * Delete all attachments for this entity.
     *
     * @throws AttachmentException
     */
    public function deleteAttachments(): bool
    {
        $attachmentEntry = $this->getAttachmentEntry();

        if ($attachmentEntry === null || $attachmentEntry === -1) {
            return true; // Nothing to delete
        }

        return $this->getAttachmentsManager()->delete($attachmentEntry);
    }

    /**
     * Get the AttachmentEntry value from the entity.
     */
    protected function getAttachmentEntry(): ?int
    {
        // Try to get from attributes
        $entry = $this->getAttribute('AttachmentEntry');

        if ($entry === null || $entry === -1) {
            return null;
        }

        return (int) $entry;
    }

    /**
     * Get the key to use for attachment operations.
     * Override in model if using a different primary key.
     */
    protected function getAttachmentKey(): int|string
    {
        // Try DocEntry first (for documents)
        if (isset($this->attributes['DocEntry'])) {
            return (int) $this->attributes['DocEntry'];
        }

        // Try CardCode (for BusinessPartners)
        if (isset($this->attributes['CardCode'])) {
            return (string) $this->attributes['CardCode'];
        }

        // Try ItemCode (for Items)
        if (isset($this->attributes['ItemCode'])) {
            return (string) $this->attributes['ItemCode'];
        }

        // Fallback to primary key
        return $this->getKey();
    }

    /**
     * Get the endpoint to use for attachment operations.
     * Override in model if using a different endpoint.
     */
    protected function getAttachmentEndpoint(): string
    {
        // Use the entity name from the model
        return static::getEntityName();
    }

    /**
     * Get the attachments manager from SDK.
     */
    protected function getAttachmentsManager(): \SapB1\Client\AttachmentsManager
    {
        return SapB1::attachments();
    }

    /**
     * Get the entity name for this model.
     * Should be overridden in the model class.
     */
    abstract protected static function getEntityName(): string;

    /**
     * Get an attribute value.
     * Should be provided by the model class.
     *
     * @return mixed
     */
    abstract public function getAttribute(string $key);

    /**
     * Get the primary key value.
     * Should be provided by the model class.
     */
    abstract public function getKey(): int|string;
}
