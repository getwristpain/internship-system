<?php

return [
    // Error Messages
    'error' => [
        'backup_failed' => 'An error occurred while trying to back up :context.',
        'create_failed' => 'An error occurred while creating a new :context.',
        'delete_failed' => 'An error occurred while deleting :context.',
        'download_failed' => 'An error occurred while downloading :context.',
        'duplicate' => ':context already exists.',
        'fetch_failed' => 'An error occurred while fetching :context.',
        'format_failed' => 'An error occurred while formatting :context.',
        'generate_failed' => 'An error occurred while generating :context.',
        'in_use' => ':context is currently in use and cannot be deleted.',
        'invalid' => ':context is invalid.',
        'lock_failed' => 'An error occurred while locking :context.',
        'message' => 'An error occurred on the :context.',
        'missing' => 'Required :context not found.',
        'not_found' => ':context not found.',
        'receive_failed' => 'An error occurred while receiving :context.',
        'send_failed' => 'An error occurred while sending :context.',
        'store_failed' => 'An error occurred while storing :context.',
        'unauthorized' => 'You do not have permission to perform this action on :context.',
        'unlock_failed' => 'An error occurred while unlocking :context.',
        'update_failed' => 'An error occurred while updating :context.',
        'upload_failed' => 'An error occurred while uploading :context.',
    ],

    // Information Messages
    'info' => [
        'approved' => ':context approval succeeded.',
        'backed_up' => ':context was successfully backed up.',
        'deleted' => ':context was successfully deleted.',
        'not_modified' => ':context was not modified.',
        'saved' => ':context was successfully saved.',
        'syncing' => 'Syncing :context is in progress, please wait.',
        'updated' => ':context was successfully updated.',
        'uploaded' => ':context was successfully uploaded.',
        'verified' => ':context verification succeeded.',
    ],

    // Success Messages
    'success' => [
        'create' => ':context was successfully created!',
        'delete' => ':context was successfully deleted!',
        'exported' => ':context was successfully exported.',
        'imported' => ':context was successfully imported.',
        'locked' => ':context was successfully locked.',
        'processed' => ':context was successfully processed.',
        'store' => ':context was successfully stored!',
        'synced' => ':context was successfully synced.',
        'unlocked' => ':context was successfully unlocked.',
        'update' => ':context was successfully updated!',
    ],

    // Warning Messages
    'warning' => [
        'already_exists' => ':context already exists, are you sure you want to proceed?',
        'approving' => ':context is awaiting approval, please wait for the approval process.',
        'incomplete' => ':context is incomplete, please ensure all fields are filled correctly.',
        'not_verified' => ':context is not verified, proceed with caution.',
        'pending' => ':context is still pending, changes cannot be made at this time.',
    ],
];
