<?php

return [

    /*
    |--------------------------------------------------------------------------
    | CORS — Cross-Origin Resource Sharing
    |--------------------------------------------------------------------------
    | Permite que el HTML externo (desde otra carpeta o dominio)
    | pueda consumir la API de Laravel sin ser bloqueado por el navegador.
    */

    'paths' => ['api/*'],          // Solo afecta rutas /api/...

    'allowed_methods' => ['*'],    // GET, POST, PUT, DELETE, OPTIONS

    'allowed_origins' => ['*'],    // Cualquier origen (para desarrollo)

    'allowed_origins_patterns' => [],

    'allowed_headers' => ['*'],    // Content-Type, Authorization, etc.

    'exposed_headers' => [],

    'max_age' => 0,

    'supports_credentials' => false,

];
