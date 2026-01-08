<?php

declare(strict_types=1);

use SapB1\Toolkit\Models\Concerns\HasAttachments;

it('trait exists', function () {
    expect(trait_exists(HasAttachments::class))->toBeTrue();
});

it('has addAttachment method', function () {
    $methods = get_class_methods(HasAttachments::class);

    expect(in_array('addAttachment', $methods))->toBeTrue();
});

it('has attachments method', function () {
    $methods = get_class_methods(HasAttachments::class);

    expect(in_array('attachments', $methods))->toBeTrue();
});

it('has downloadAttachment method', function () {
    $methods = get_class_methods(HasAttachments::class);

    expect(in_array('downloadAttachment', $methods))->toBeTrue();
});

it('has downloadAttachmentTo method', function () {
    $methods = get_class_methods(HasAttachments::class);

    expect(in_array('downloadAttachmentTo', $methods))->toBeTrue();
});

it('has hasAttachments method', function () {
    $methods = get_class_methods(HasAttachments::class);

    expect(in_array('hasAttachments', $methods))->toBeTrue();
});

it('has attachmentCount method', function () {
    $methods = get_class_methods(HasAttachments::class);

    expect(in_array('attachmentCount', $methods))->toBeTrue();
});

it('has attachmentMetadata method', function () {
    $methods = get_class_methods(HasAttachments::class);

    expect(in_array('attachmentMetadata', $methods))->toBeTrue();
});

it('has deleteAttachments method', function () {
    $methods = get_class_methods(HasAttachments::class);

    expect(in_array('deleteAttachments', $methods))->toBeTrue();
});
