<?php

/* This configuration is used to run the tests */

return  [
  'app' => [
    'salt' => 'replacewithrandomstring',
  ],
  'services' => [
    'auth' => 'App\Auth\Services\Auth',
    'db' => 'Infuse\Services\Database',
    'model_driver' => 'Infuse\Services\ModelDriver',
    'pdo' => 'Infuse\Services\Pdo',
  ],
  'modules' => [
    'middleware' => [
      'auth',
    ],
  ],
  'database' => [
    'type' => 'mysql',
    'user' => 'root',
    'password' => '',
    'host' => '127.0.0.1',
    'name' => 'mydb',
  ],
  'sessions' => [
    'enabled' => true,
    'adapter' => 'database',
    'lifetime' => 86400,
  ],
];
