<?php
echo 1;
echo getenv('OPENSHIFT_MYSQL_DB_HOST');
echo 2;
echo getenv('$OPENSHIFT_MYSQL_DB_HOST');
echo 3;
echo $_ENV['$OPENSHIFT_MYSQL_DB_HOST'];
echo 4;
echo $_SERVER['$OPENSHIFT_MYSQL_DB_HOST'];
echo 5;
echo OPENSHIFT_MYSQL_DB_HOST;
echo 6;
var_dump($_ENV);
var_dump($_SERVER);