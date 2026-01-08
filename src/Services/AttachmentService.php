<?php

declare(strict_types=1);

namespace SapB1\Toolkit\Services;

use Illuminate\Http\UploadedFile;
use SapB1\Exceptions\AttachmentException;

/**
 * Service for managing file attachments in SAP B1.
 *
 * Provides a high-level interface for uploading, downloading, listing,
 * and deleting file attachments for SAP B1 entities like orders,
 * invoices, and business partners.
 *
 * Uses the SDK's AttachmentsManager which interacts with the
 * Attachments2 endpoint in Service Layer.
 *
 * @example
 * ```php
 * $service = app(AttachmentService::class);
 *
 * // Upload to order
 * $attachment = $service->uploadToOrder(123, $file);
 *
 * // List attachments
 * $attachments = $service->listForOrder(123);
 *
 * // Download
 * $content = $service->download($attachmentEntry);
 * ```
 */
final class AttachmentService extends BaseService
{
    /**
     * Upload a file to an order.
     *
     * @param  UploadedFile|string  $file  File or path to file
     * @param  string|null  $fileName  Optional custom filename
     * @return array{AbsoluteEntry: int, FileName: string}
     *
     * @throws AttachmentException
     */
    public function uploadToOrder(int $docEntry, UploadedFile|string $file, ?string $fileName = null): array
    {
        return $this->upload('Orders', $docEntry, $file, $fileName);
    }

    /**
     * Upload a file to an invoice.
     *
     * @param  UploadedFile|string  $file  File or path to file
     * @param  string|null  $fileName  Optional custom filename
     * @return array{AbsoluteEntry: int, FileName: string}
     *
     * @throws AttachmentException
     */
    public function uploadToInvoice(int $docEntry, UploadedFile|string $file, ?string $fileName = null): array
    {
        return $this->upload('Invoices', $docEntry, $file, $fileName);
    }

    /**
     * Upload a file to a delivery note.
     *
     * @param  UploadedFile|string  $file  File or path to file
     * @param  string|null  $fileName  Optional custom filename
     * @return array{AbsoluteEntry: int, FileName: string}
     *
     * @throws AttachmentException
     */
    public function uploadToDelivery(int $docEntry, UploadedFile|string $file, ?string $fileName = null): array
    {
        return $this->upload('DeliveryNotes', $docEntry, $file, $fileName);
    }

    /**
     * Upload a file to a purchase order.
     *
     * @param  UploadedFile|string  $file  File or path to file
     * @param  string|null  $fileName  Optional custom filename
     * @return array{AbsoluteEntry: int, FileName: string}
     *
     * @throws AttachmentException
     */
    public function uploadToPurchaseOrder(int $docEntry, UploadedFile|string $file, ?string $fileName = null): array
    {
        return $this->upload('PurchaseOrders', $docEntry, $file, $fileName);
    }

    /**
     * Upload a file to a purchase invoice.
     *
     * @param  UploadedFile|string  $file  File or path to file
     * @param  string|null  $fileName  Optional custom filename
     * @return array{AbsoluteEntry: int, FileName: string}
     *
     * @throws AttachmentException
     */
    public function uploadToPurchaseInvoice(int $docEntry, UploadedFile|string $file, ?string $fileName = null): array
    {
        return $this->upload('PurchaseInvoices', $docEntry, $file, $fileName);
    }

    /**
     * Upload a file to a business partner.
     *
     * @param  UploadedFile|string  $file  File or path to file
     * @param  string|null  $fileName  Optional custom filename
     * @return array{AbsoluteEntry: int, FileName: string}
     *
     * @throws AttachmentException
     */
    public function uploadToPartner(string $cardCode, UploadedFile|string $file, ?string $fileName = null): array
    {
        return $this->upload('BusinessPartners', $cardCode, $file, $fileName);
    }

    /**
     * Upload a file to an item.
     *
     * @param  UploadedFile|string  $file  File or path to file
     * @param  string|null  $fileName  Optional custom filename
     * @return array{AbsoluteEntry: int, FileName: string}
     *
     * @throws AttachmentException
     */
    public function uploadToItem(string $itemCode, UploadedFile|string $file, ?string $fileName = null): array
    {
        return $this->upload('Items', $itemCode, $file, $fileName);
    }

    /**
     * Upload a file to a quotation.
     *
     * @param  UploadedFile|string  $file  File or path to file
     * @param  string|null  $fileName  Optional custom filename
     * @return array{AbsoluteEntry: int, FileName: string}
     *
     * @throws AttachmentException
     */
    public function uploadToQuotation(int $docEntry, UploadedFile|string $file, ?string $fileName = null): array
    {
        return $this->upload('Quotations', $docEntry, $file, $fileName);
    }

    /**
     * Upload a file to a journal entry.
     *
     * @param  UploadedFile|string  $file  File or path to file
     * @param  string|null  $fileName  Optional custom filename
     * @return array{AbsoluteEntry: int, FileName: string}
     *
     * @throws AttachmentException
     */
    public function uploadToJournalEntry(int $jdtNum, UploadedFile|string $file, ?string $fileName = null): array
    {
        return $this->upload('JournalEntries', $jdtNum, $file, $fileName);
    }

    /**
     * Upload a file to any SAP B1 entity.
     *
     * @param  string  $endpoint  The entity endpoint (e.g., 'Orders', 'BusinessPartners')
     * @param  int|string  $key  The entity key (DocEntry, CardCode, etc.)
     * @param  UploadedFile|string  $file  File or path to file
     * @param  string|null  $fileName  Optional custom filename
     * @return array{AbsoluteEntry: int, FileName: string}
     *
     * @throws AttachmentException
     */
    public function upload(string $endpoint, int|string $key, UploadedFile|string $file, ?string $fileName = null): array
    {
        return $this->client()->attachments()->upload($endpoint, $key, $file, $fileName);
    }

    /**
     * Download an attachment by its entry number.
     *
     * @param  int  $attachmentEntry  The attachment AbsoluteEntry
     * @return string The file content
     *
     * @throws AttachmentException
     */
    public function download(int $attachmentEntry): string
    {
        return $this->client()->attachments()->download($attachmentEntry);
    }

    /**
     * Download an attachment to a specified directory.
     *
     * @param  int  $attachmentEntry  The attachment AbsoluteEntry
     * @param  string  $savePath  Directory path to save the file
     * @return string The file content
     *
     * @throws AttachmentException
     */
    public function downloadTo(int $attachmentEntry, string $savePath): string
    {
        return $this->client()->attachments()->download($attachmentEntry, $savePath);
    }

    /**
     * List attachments for an order.
     *
     * @return array<int, array<string, mixed>>
     *
     * @throws AttachmentException
     */
    public function listForOrder(int $docEntry): array
    {
        return $this->listFor('Orders', $docEntry);
    }

    /**
     * List attachments for an invoice.
     *
     * @return array<int, array<string, mixed>>
     *
     * @throws AttachmentException
     */
    public function listForInvoice(int $docEntry): array
    {
        return $this->listFor('Invoices', $docEntry);
    }

    /**
     * List attachments for a delivery note.
     *
     * @return array<int, array<string, mixed>>
     *
     * @throws AttachmentException
     */
    public function listForDelivery(int $docEntry): array
    {
        return $this->listFor('DeliveryNotes', $docEntry);
    }

    /**
     * List attachments for a business partner.
     *
     * @return array<int, array<string, mixed>>
     *
     * @throws AttachmentException
     */
    public function listForPartner(string $cardCode): array
    {
        return $this->listFor('BusinessPartners', $cardCode);
    }

    /**
     * List attachments for an item.
     *
     * @return array<int, array<string, mixed>>
     *
     * @throws AttachmentException
     */
    public function listForItem(string $itemCode): array
    {
        return $this->listFor('Items', $itemCode);
    }

    /**
     * List attachments for any SAP B1 entity.
     *
     * @param  string  $endpoint  The entity endpoint
     * @param  int|string  $key  The entity key
     * @return array<int, array<string, mixed>>
     *
     * @throws AttachmentException
     */
    public function listFor(string $endpoint, int|string $key): array
    {
        return $this->client()->attachments()->list($endpoint, $key);
    }

    /**
     * Get attachment metadata.
     *
     * @param  int  $attachmentEntry  The attachment AbsoluteEntry
     * @return array<string, mixed>
     *
     * @throws AttachmentException
     */
    public function metadata(int $attachmentEntry): array
    {
        return $this->client()->attachments()->metadata($attachmentEntry);
    }

    /**
     * Delete an attachment.
     *
     * @param  int  $attachmentEntry  The attachment AbsoluteEntry
     *
     * @throws AttachmentException
     */
    public function delete(int $attachmentEntry): bool
    {
        return $this->client()->attachments()->delete($attachmentEntry);
    }

    /**
     * Check if an entity has attachments.
     *
     * @param  string  $endpoint  The entity endpoint
     * @param  int|string  $key  The entity key
     */
    public function hasAttachments(string $endpoint, int|string $key): bool
    {
        $attachments = $this->listFor($endpoint, $key);

        return count($attachments) > 0;
    }

    /**
     * Get attachment count for an entity.
     *
     * @param  string  $endpoint  The entity endpoint
     * @param  int|string  $key  The entity key
     */
    public function countFor(string $endpoint, int|string $key): int
    {
        return count($this->listFor($endpoint, $key));
    }
}
