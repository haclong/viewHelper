<?php
/**
 * Global Configuration Override
 *
 * You can use this file for overriding configuration values from modules, etc.
 * You would place values in here that are agnostic to the environment and not
 * sensitive to security.
 *
 * @NOTE: In practice, this file will typically be INCLUDED in your source
 * control, so do not include passwords or other sensitive information in this
 * file.
 */

return array(
    'caches' => array(
        'Cache\Persistence' => array(
            'adapter' => 'filesystem',
            'ttl'     => 86400,
            'options' => array(
                // mod : 775 - owner : user:www-data
                'cache_dir' => __DIR__ . '/../../data/cache/',
            ),
        ),
    ),
);