<?php
// First, publish the Media Library config if you haven't already:
// php artisan vendor:publish --provider="Spatie\MediaLibrary\MediaLibraryServiceProvider" --tag="medialibrary-config"

// config/media-library.php
return [
    /*
     * The disk on which to store added files and derived images by default. Choose
     * one or more of the disks you've configured in config/filesystems.php.
     */
    'disk_name' => env('MEDIA_DISK', 'public'),

    /*
     * The maximum file size of an item in bytes.
     * Adding a larger file will result in an exception.
     */
    'max_file_size' => 1024 * 1024 * 100, // 100MB

    /*
     * This queue connection will be used to generate derived and responsive images.
     * Leave empty to use the default queue connection.
     */
    'queue_connection_name' => env('QUEUE_CONNECTION', 'sync'),

    /*
     * This queue will be used to generate derived and responsive images.
     * Leave empty to use the default queue.
     */
    'queue_name' => '',

    /*
     * By default all conversions will be performed on a queue.
     */
    'queue_conversions_by_default' => env('QUEUE_CONVERSIONS_BY_DEFAULT', true),

    /*
     * The fully qualified class name of the media model.
     */
    'media_model' => Spatie\MediaLibrary\MediaCollections\Models\Media::class,

    /*
     * The fully qualified class name of the model used for temporary uploads.
     */
    'temporary_upload_model' => Spatie\MediaLibrary\MediaCollections\Models\Media::class,

    /*
     * When enabled, media collections will be serialised using the media library.
     * This can be useful when using media collections in API responses.
     */
    'enable_temporary_uploads_session_affinity' => true,

    /*
     * When enabled, media collections will be serialised using the media library.
     */
    'generate_thumbnails_for_temporary_uploads' => true,

    /*
     * This is the class that is responsible for naming generated files.
     */
    'file_namer' => Spatie\MediaLibrary\Support\FileNamer\DefaultFileNamer::class,

    /*
     * The class that contains the strategy for determining a media file's path.
     */
    'path_generator' => Spatie\MediaLibrary\Support\PathGenerator\DefaultPathGenerator::class,

    /*
     * When urls to files get generated, this class will be called. Use the default
     * if your files are stored locally above the site root or on s3.
     */
    'url_generator' => Spatie\MediaLibrary\Support\UrlGenerator\DefaultUrlGenerator::class,

    /*
     * Medialibrary will try to optimize all converted images by removing
     * metadata and applying a little bit of compression. These are
     * the optimizers that will be used by default.
     */
    'image_optimizers' => [
        Spatie\ImageOptimizer\Optimizers\Jpegoptim::class => [
            '-m85', // set maximum quality to 85%
            '--force', // ensure that progressive generation is always done also if a little bigger
            '--strip-all', // this strips out all text information such as comments and EXIF data
            '--all-progressive', // this will make sure the resulting image is a progressive one
        ],
        Spatie\ImageOptimizer\Optimizers\Pngquant::class => [
            '--force', // required parameter for this package
        ],
        Spatie\ImageOptimizer\Optimizers\Optipng::class => [
            '-i0', // this will result in a non-interlaced, progressive scanned image
            '-o2', // this set the optimization level to two (multiple IDAT compression trials)
            '-quiet', // required parameter for this package
        ],
        Spatie\ImageOptimizer\Optimizers\Svgo::class => [
            '--disable=cleanupIDs', // disabling because it is known to cause troubles
        ],
        Spatie\ImageOptimizer\Optimizers\Gifsicle::class => [
            '-b', // required parameter for this package
            '-O3', // this produces the slowest but best results
        ],
        Spatie\ImageOptimizer\Optimizers\Cwebp::class => [
            '-m', '6', // for the slowest compression method in order to get the best compression.
            '-pass', '10', // for maximizing the amount of analysis pass.
            '-mt', // multithreading for some speed improvements.
            '-q', '90', //quality factor that brings the least noticeable changes.
        ],
    ],

    /*
     * These generators will be used to create an image of media files.
     */
    'image_generators' => [
        Spatie\MediaLibrary\Conversions\ImageGenerators\Image::class,
        Spatie\MediaLibrary\Conversions\ImageGenerators\Webp::class,
        Spatie\MediaLibrary\Conversions\ImageGenerators\Pdf::class,
        Spatie\MediaLibrary\Conversions\ImageGenerators\Svg::class,
        Spatie\MediaLibrary\Conversions\ImageGenerators\Video::class,
    ],

    /*
     * The class that contains the strategy for determining how to deal with orphaned media files.
     */
    'media_disk_cleanup_strategy' => 'Spatie\MediaLibrary\MediaCleanup\MediaCleanupStrategy',

    /*
     * The engine that should perform the image conversions.
     * Should be either `gd` or `imagick`.
     */
    'image_driver' => env('IMAGE_DRIVER', 'gd'),

    /*
     * FFMPEG & FFProbe binaries paths, only used if you try to generate video
     * thumbnails and have installed the php-ffmpeg/php-ffmpeg composer package.
     */
    'ffmpeg_path' => env('FFMPEG_PATH', '/usr/bin/ffmpeg'),
    'ffprobe_path' => env('FFPROBE_PATH', '/usr/bin/ffprobe'),

    /*
     * The path where to store temporary files while performing image conversions.
     * If set to null, storage_path('medialibrary/temp') will be used.
     */
    'temporary_directory_path' => null,

    /*
     * Here you can override the class names of the jobs used by this package. Make sure
     * your custom jobs extend their original jobs.
     */
    'jobs' => [
        'perform_conversions' => Spatie\MediaLibrary\Conversions\Jobs\PerformConversionsJob::class,
    ],

    /*
     * When using the addMediaFromUrl method, you may want to replace the default downloader.
     * This is particularly useful when the url of the image is behind a paywall and
     * needs additional authentication.
     */
    'media_downloader' => Spatie\MediaLibrary\Downloaders\DefaultDownloader::class,

    /*
     * When converting Media instances to response the media library will add
     * a `loading` attribute to the `img` tag. Here you can set the default value
     * of that attribute.
     */
    'default_loading_attribute_value' => null,

    /*
     * You can specify a prefix that will be used to generate urls to files.
     * The prefix will be used like this: `{prefix}/media/{id}/conversions/file.jpg`
     */
    'prefix' => env('MEDIA_PREFIX', ''),
];
