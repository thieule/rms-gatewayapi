<?php

return (static function() {
    $configTemplate = [
        // List of microservices behind the gateway
        'services' => [
            'rms-notification' => [],
            'login' => []
        ],

        // Array of extra (eg. aggregated) routes
        'routes' => [
            [
                'aggregate' => true,
                'method' => 'GET',
                'path' => 'notification/config',
                'actions' => [
                    'my-config' => [
                        'service' => 'rms-notification',
                        'method' => 'GET',
                        'path' => 'my-config',
                        'sequence' => 0,
                        'critical' => true
                    ],
                    'all-config' => [
                        'service' => 'rms-notification',
                        'method' => 'GET',
                        'path' => 'resource/config',
                        'sequence' => 0,
                        'critical' => true
                    ],
                    'item-config' => [
                        'service' => 'rms-notification',
                        'method' => 'GET',
                        'path' => 'resource/config/1',
                        'sequence' => 0,
                        'critical' => true
                    ]
                ]
            ]
        ],

        // Global parameters
        'global' => [
            'prefix' => '/v1',
            'timeout' => 5.0,
            'doc_point' => '/api/doc',
            'domain' => 'local'
        ],
    ];

    $sections = ['services', 'routes', 'global'];

    foreach ($sections as $section) {
        $config = env('GATEWAY_' . strtoupper($section), false);
        ${$section} = $config ? json_decode($config, true) : $configTemplate[$section];
        if (${$section} === null) throw new \Exception('Unable to decode GATEWAY_' . strtoupper($section) . ' variable');
    }

    return compact($sections);
})();
