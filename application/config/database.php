<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
| -------------------------------------------------------------------
| DATABASE CONNECTIVITY SETTINGS
| -------------------------------------------------------------------
| This file will contain the settings needed to access your database.
|
| For complete instructions please consult the 'Database Connection'
| page of the User Guide.
|
| -------------------------------------------------------------------
| EXPLANATION OF VARIABLES
| -------------------------------------------------------------------
|
|	['hostname'] The hostname of your database server.
|	['username'] The username used to connect to the database
|	['password'] The password used to connect to the database
|	['database'] The name of the database you want to connect to
|	['dbdriver'] The database type. ie: mysql.  Currently supported:
				 mysql, mysqli, postgre, odbc, mssql, sqlite, oci8
|	['dbprefix'] You can add an optional prefix, which will be added
|				 to the table name when using the  Active Record class
|	['pconnect'] TRUE/FALSE - Whether to use a persistent connection
|	['db_debug'] TRUE/FALSE - Whether database errors should be displayed.
|	['cache_on'] TRUE/FALSE - Enables/disables query caching
|	['cachedir'] The path to the folder where cache files should be stored
|	['char_set'] The character set used in communicating with the database
|	['dbcollat'] The character collation used in communicating with the database
|				 NOTE: For MySQL and MySQLi databases, this setting is only used
| 				 as a backup if your server is running PHP < 5.2.3 or MySQL < 5.0.7
|				 (and in table creation queries made with DB Forge).
| 				 There is an incompatibility in PHP with mysql_real_escape_string() which
| 				 can make your site vulnerable to SQL injection if you are using a
| 				 multi-byte character set and are running versions lower than these.
| 				 Sites using Latin-1 or UTF-8 database character set and collation are unaffected.
|	['swap_pre'] A default table prefix that should be swapped with the dbprefix
|	['autoinit'] Whether or not to automatically initialize the database.
|	['stricton'] TRUE/FALSE - forces 'Strict Mode' connections
|							- good for ensuring strict SQL while developing
|
| The $active_group variable lets you choose which connection group to
| make active.  By default there is only one group (the 'default' group).
|
| The $active_record variables lets you determine whether or not to load
| the active record class
*/

$active_group = 'default';
$active_record = TRUE;

//$db['default']['hostname'] = '200.41.6.123';
$db['default']['hostname'] = '10.221.16.88';
//$db['default']['hostname']='localhost'
$db['default']['username'] = 'postgres';
$db['default']['password'] = '123';
$db['default']['database'] = 'contactosms';
$db['default']['dbdriver'] = 'postgre';
$db['default']['dbprefix'] = '';
$db['default']['pconnect'] = FALSE;
$db['default']['db_debug'] = FALSE;
$db['default']['cache_on'] = FALSE;
$db['default']['cachedir'] = '';
$db['default']['char_set'] = 'utf8';
$db['default']['dbcollat'] = 'utf8_general_ci';
$db['default']['swap_pre'] = '';
$db['default']['autoinit'] = TRUE;
$db['default']['stricton'] = FALSE;
$db['default']['port'] = 5432; 


//$db['produccion']['hostname'] = '200.41.6.123';
$db['produccion']['hostname'] = '10.221.16.88';
//$db['produccion']['hostname']='localhost'
$db['produccion']['username'] = 'postgres';
$db['produccion']['password'] = '123';
$db['produccion']['database'] = 'contactosms';
$db['produccion']['dbdriver'] = 'postgre';
$db['produccion']['dbprefix'] = '';
$db['produccion']['pconnect'] = FALSE;
$db['produccion']['db_debug'] = FALSE;
$db['produccion']['cache_on'] = FALSE;
$db['produccion']['cachedir'] = '';
$db['produccion']['char_set'] = 'utf8';
$db['produccion']['dbcollat'] = 'utf8_general_ci';
$db['produccion']['swap_pre'] = '';
$db['produccion']['autoinit'] = TRUE;
$db['produccion']['stricton'] = FALSE;
$db['produccion']['port'] = 5432; 

//$db['produccion']['hostname'] = '200.41.6.123';
$db['dbmysql']['hostname'] = '10.125.15.2';
//$db['produccion']['hostname']='localhost'
$db['dbmysql']['username'] = 'root';
$db['dbmysql']['password'] = 'toor';
$db['dbmysql']['database'] = 'smsapp';
$db['dbmysql']['dbdriver'] = 'mysql';
$db['dbmysql']['dbprefix'] = '';
$db['dbmysql']['pconnect'] = FALSE;
$db['dbmysql']['db_debug'] = FALSE;
$db['dbmysql']['cache_on'] = FALSE;
$db['dbmysql']['cachedir'] = '';
$db['dbmysql']['char_set'] = 'utf8';
$db['dbmysql']['dbcollat'] = 'utf8_general_ci';
$db['dbmysql']['swap_pre'] = '';
$db['dbmysql']['autoinit'] = TRUE;
$db['dbmysql']['stricton'] = FALSE;
//$db['mysql']['port'] = 5432; 


$db['default']['hostname'] = '10.221.16.88';
//$db['default']['hostname']='localhost'
$db['natura']['username'] = 'postgres';
$db['natura']['password'] = '123';
$db['natura']['database'] = 'natura';
$db['natura']['dbdriver'] = 'postgre';
$db['natura']['dbprefix'] = '';
$db['natura']['pconnect'] = FALSE;
$db['natura']['db_debug'] = FALSE;
$db['natura']['cache_on'] = FALSE;
$db['natura']['cachedir'] = '';
$db['natura']['char_set'] = 'utf8';
$db['natura']['dbcollat'] = 'utf8_general_ci';
$db['natura']['swap_pre'] = '';
$db['natura']['autoinit'] = TRUE;
$db['natura']['stricton'] = FALSE;
$db['natura']['port'] = 5432;



/* End of file database.php */
/* Location: ./application/config/database.php */
