<?php

return [
    // System Action
    'context' => array_merge(
        require __DIR__ . './../id/attribute.php',
        ['cache_key' => 'cache key'],
    ),

    // Error Messages
    'error' => [
        'backup_failed' => ':context failed to back up.',
        'create_failed' => ':context failed to create.',
        'delete_failed' => ':context failed to delete.',
        'download_failed' => ':context failed to download.',
        'duplicate' => ':context already exists.',
        'fetch_failed' => ':context failed to fetch.',
        'format_failed' => ':context failed to format.',
        'generate_failed' => 'Failed to generate :context.',
        'proccess_failed' => 'Failed to process :action.',
        'in_use' => ':context is in use and cannot be deleted.',
        'invalid' => ':context is invalid.',
        'lock_failed' => ':context failed to lock.',
        'message' => ':context encountered an error.',
        'method_not_found' => ':method method not found.',
        'missing' => 'Required :context not found.',
        'not_found' => ':context not found.',
        'receive_failed' => ':context failed to receive.',
        'send_failed' => ':context failed to send.',
        'store_failed' => ':context failed to store.',
        'unauthorized' => 'You do not have permission to :action.',
        'unlock_failed' => ':context failed to unlock.',
        'update_failed' => ':context failed to update.',
        'upload_failed' => ':context failed to upload.',
    ],

    // Information Messages
    'info' => [
        'approved' => ':context has been successfully approved.',
        'backed_up' => ':context has been successfully backed up.',
        'not_modified' => ':context has not been modified.',
        'syncing' => ':context is syncing, please wait.',
    ],

    // Success Messages
    'success' => [
        'created' => ':context has been successfully created.',
        'deleted' => ':context has been successfully deleted.',
        'exported' => ':context has been successfully exported.',
        'imported' => ':context has been successfully imported.',
        'locked' => ':context has been successfully locked.',
        'processed' => ':context has been successfully processed.',
        'saved' => ':context has been successfully saved.',
        'synced' => ':context has been successfully synced.',
        'unlocked' => ':context has been successfully unlocked.',
        'updated' => ':context has been successfully updated.',
        'uploaded' => ':context has been successfully uploaded.',
        'verified' => ':context has been successfully verified.',
    ],

    // Warning Messages
    'warning' => [
        'already_exists' => ':context already exists, are you sure you want to continue?',
        'approving' => ':context is awaiting approval, please wait for the approval process.',
        'incomplete' => ':context is incomplete, please ensure all fields are correctly filled.',
        'not_verified' => ':context is not verified, proceed with caution.',
        'pending' => ':context is still pending, changes cannot be made at this time.',
    ],
];
