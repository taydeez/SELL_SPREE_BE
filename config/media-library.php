<?php

return [
    /*
     * Use UUID-based paths so we can pre-calculate the R2 key before the
     * Media record exists — enabling true presigned direct-to-R2 uploads.
     */
    'path_generator' => \App\Support\UuidPathGenerator::class,

    /*
     * Default disk for media storage.
     */
    'disk_name' => env('MEDIA_DISK', 'r2'),

    /*
     * Maximum file size (bytes). 500 MB.
     */
    'max_file_size' => 1024 * 1024 * 500,

    /*
     * Queue conversions on the default queue.
     */
    'queue_conversions_by_default' => env('QUEUE_CONVERSIONS_BY_DEFAULT', true),

    /*
     * The fully qualified class name of the media model.
     */
    'media_model' => Spatie\MediaLibrary\MediaCollections\Models\Media::class,

    /*
     * Prefix for all R2 object URLs. Leave empty to use the disk URL.
     */
    'prefix' => '',

    /*
     * Temporary URL expiry in seconds for private disks.
     */
    'temporary_url_expiry' => env('MEDIA_SIGNED_URL_EXPIRY', 3600),
];
