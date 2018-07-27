<?php
return array (
  'session' => 
  array (
    'save' => 'redis',
    'redis' => 
    array (
      'host' => '127.0.0.1',
      'port' => '6379',
      'password' => '',
      'timeout' => '2.5',
      'persistent_identifier' => '',
      'database' => '2',
      'compression_threshold' => '2048',
      'compression_library' => 'gzip',
      'log_level' => '1',
      'max_concurrency' => '6',
      'break_after_frontend' => '5',
      'break_after_adminhtml' => '30',
      'first_lifetime' => '600',
      'bot_first_lifetime' => '60',
      'bot_lifetime' => '7200',
      'disable_locking' => '0',
      'min_lifetime' => '60',
      'max_lifetime' => '2592000',
    ),
  ),
  'cache' => 
  array (
    'frontend' => 
    array (
      'default' => 
      array (
        'backend' => 'Cm_Cache_Backend_Redis',
        'backend_options' => 
        array (
          'server' => '127.0.0.1',
          'database' => '0',
          'port' => '6379',
        ),
      ),
    ),
  ),
  'backend' => 
  array (
    'frontName' => 'admin_1fhpy1',
  ),
  'crypt' => 
  array (
    'key' => 'adbc441f16b39b4fb48239600162c3b7',
  ),
  'db' => 
  array (
    'table_prefix' => '',
    'connection' => 
    array (
      'default' => 
      array (
        'host' => '127.0.0.1:3306',
        'dbname' => 'watchshopping',
        'username' => 'root',
        'password' => 'root',
        'active' => '1',
        'model' => 'mysql4',
        'engine' => 'innodb',
        'initStatements' => 'SET NAMES utf8;',
      ),
    ),
  ),
  'resource' => 
  array (
    'default_setup' => 
    array (
      'connection' => 'default',
    ),
  ),
  'x-frame-options' => 'SAMEORIGIN',
  'MAGE_MODE' => 'developer',
  'cache_types' => 
  array (
    'config' => 1,
    'layout' => 0,
    'block_html' => 0,
    'collections' => 0,
    'reflection' => 1,
    'db_ddl' => 1,
    'eav' => 1,
    'customer_notification' => 0,
    'config_integration' => 0,
    'config_integration_api' => 0,
    'full_page' => 0,
    'translate' => 0,
    'config_webservice' => 0,
    'compiled_config' => 1,
  ),
  'install' => 
  array (
    'date' => 'Mon, 14 May 2018 07:26:45 +0000',
  ),
  'db_logger' => 
  array (
    'output' => 'file',
    'log_everything' => 1,
    'query_time_threshold' => '0.001',
    'include_stacktrace' => 1,
  ),
  'system' => 
  array (
    'default' => 
    array (
      'dev' => 
      array (
        'debug' => 
        array (
          'debug_logging' => '0',
        ),
      ),
    ),
  ),
);
