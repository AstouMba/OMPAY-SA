<?php

return [
    'default' => 'default',

    'documentations' => [
        'default' => [

            'api' => [
                'title' => 'OmPay API Documentation',
            ],

            'routes' => [
                // Route Swagger UI
                'api' => 'api/documentation',

                // Ne surtout pas toucher sinon 404 UI
                'docs' => 'docs',
            ],

            'paths' => [
                // ✅ Chemin correct en prod Render
                'docs' => base_path('storage/api-docs'),

                // Laisser en false sinon paths deviennent en absolu domaine root
                'use_absolute_path' => false,

                // Assets Swagger UI
                'swagger_ui_assets_path' => 'vendor/swagger-api/swagger-ui/dist/',

                // Nom du JSON Swagger à servir
                'docs_json' => 'api-docs.json',
                'docs_yaml' => 'api-docs.yaml',

                // Format généré (JSON)
                'format_to_use_for_docs' => 'json',

                // ✅ Scan les annotations dans app/
                'annotations' => [
                    base_path('app'),
                ],

                'base' => env('L5_SWAGGER_BASE_PATH', null),
                'excludes' => [],
            ],
        ],
    ],

    'defaults' => [

        'routes' => [
            'docs' => 'docs',
            'oauth2_callback' => 'api/oauth2-callback',

            'middleware' => [
                'api' => [],
                'asset' => [],
                'docs' => [],
                'oauth2_callback' => [],
            ],

            'group_options' => [],
        ],

        'paths' => [
            // ✅ Correction ici aussi → même dossier
            'docs' => base_path('storage/api-docs'),

            'views' => base_path('resources/views/vendor/l5-swagger'),
            'base' => env('L5_SWAGGER_BASE_PATH', null),
            'excludes' => [],
        ],

        'scanOptions' => [
            'default_processors_configuration' => [],
            'analyser' => null,
            'analysis' => null,
            'processors' => [],
            'pattern' => null,
            'exclude' => [],
            'open_api_spec_version' => env('L5_SWAGGER_OPEN_API_SPEC_VERSION', \L5Swagger\Generator::OPEN_API_DEFAULT_SPEC_VERSION),
        ],

        'securityDefinitions' => [
            'securitySchemes' => [
                'passport' => [
                    'type' => 'oauth2',
                    'description' => 'Laravel Passport OAuth2',
                    'flows' => [
                        'password' => [
                            'authorizationUrl' => env('APP_URL') . '/oauth/authorize',
                            'tokenUrl' => env('APP_URL') . '/oauth/token',
                            'refreshUrl' => env('APP_URL') . '/oauth/token/refresh',
                            'scopes' => [
                                '*' => 'All scopes',
                            ],
                        ],
                    ],
                ],
            ],
            'security' => [
                ['passport' => ['*']],
            ],
        ],

        // ✅ Force regen sur chaque déploiement → évite 404
        'generate_always' => true,

        'generate_yaml_copy' => false,
        'proxy' => null,
        'additional_config_url' => null,
        'operations_sort' => null,
        'validator_url' => null,

        'ui' => [
            'display' => [
                'dark_mode' => false,
                'doc_expansion' => 'none',
                'filter' => true,
            ],
            'authorization' => [
                'persist_authorization' => false,
                'oauth2' => [
                    'use_pkce_with_authorization_code_grant' => false,
                ],
            ],
        ],

        'constants' => [
            'L5_SWAGGER_CONST_HOST' => env('APP_URL'),
        ],
    ],
];
