<?php

return [
    'class' => 'yii\db\Connection',
    'dsn' => 'mysql:unix_socket=/Applications/MAMP/tmp/mysql/mysql.sock;dbname=kyapp',
    'username' => 'root',
    'password' => 'root',
    'charset' => 'utf8',

    // Schema cache options (for production environment)
    //'enableSchemaCache' => true,
    //'schemaCacheDuration' => 60,
    //'schemaCache' => 'cache',
];
