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

$active_group = '';
$active_record = TRUE;

if ( ENVIRONMENT == 'production' && SERVERGROUP == "1" )
{
	//log single
	$db['log']['hostname'] = '172.18.55.239';
	$db['log']['username'] = 'webuser';
	$db['log']['password'] = 'dudrud0612@';
	//login single
	$db['login']['hostname'] = '172.19.172.69';
	$db['login']['username'] = 'webuser';
	$db['login']['password'] = 'dudrud0612@';

	$db['default']['hostname'] = '172.18.45.247';
	$db['default']['username'] = 'webuser';
	$db['default']['password'] = 'dudrud0612@';
	//replication slave
	$db['default_sel']['hostname'] = '172.18.56.250';
	$db['default_sel']['username'] = 'webuser';
	$db['default_sel']['password'] = 'dudrud0612@';
	//replication master
	$db['default_ins']['hostname'] = '172.18.45.247';
	$db['default_ins']['username'] = 'webuser';
	$db['default_ins']['password'] = 'dudrud0612@';

	$db['login']['dbdriver'] = 'mysql';
	$db['login']['pconnect'] = TRUE;
	$db['log']['dbdriver'] = 'mysql';
	$db['log']['pconnect'] = TRUE;
	$db['default']['dbdriver'] = 'mysql';
	$db['default']['pconnect'] = TRUE;
	$db['default_sel']['dbdriver'] = 'mysql';
	$db['default_sel']['pconnect'] = TRUE;
	$db['default_ins']['dbdriver'] = 'mysql';
	$db['default_ins']['pconnect'] = TRUE;
}
else if ( ENVIRONMENT == 'production' && SERVERGROUP == "2" )
{
	//log single
	$db['log']['hostname'] = '172.18.55.239';
	$db['log']['username'] = 'webuser';
	$db['log']['password'] = 'dudrud0612@';
	//login single
	$db['login']['hostname'] = '172.19.172.69';
	$db['login']['username'] = 'webuser';
	$db['login']['password'] = 'dudrud0612@';

	$db['default']['hostname'] = '172.18.47.229';
	$db['default']['username'] = 'webuser';
	$db['default']['password'] = 'dudrud0612@';
	//replication slave
	$db['default_sel']['hostname'] = '172.18.45.122';
	$db['default_sel']['username'] = 'webuser';
	$db['default_sel']['password'] = 'dudrud0612@';
	//replication master
	$db['default_ins']['hostname'] = '172.18.47.229';
	$db['default_ins']['username'] = 'webuser';
	$db['default_ins']['password'] = 'dudrud0612@';

	$db['login']['dbdriver'] = 'mysql';
	$db['login']['pconnect'] = TRUE;
	$db['log']['dbdriver'] = 'mysql';
	$db['log']['pconnect'] = TRUE;
	$db['default']['dbdriver'] = 'mysql';
	$db['default']['pconnect'] = TRUE;
	$db['default_sel']['dbdriver'] = 'mysql';
	$db['default_sel']['pconnect'] = TRUE;
	$db['default_ins']['dbdriver'] = 'mysql';
	$db['default_ins']['pconnect'] = TRUE;
}
else if ( ENVIRONMENT == 'development' )
{
	// getsebool -a |grep httpd 외부에서 데이터베이스 접근하도록 설정되어있는 지 확인
	// setsebool httpd_can_network_connect_db=on 설정해야함.
	$db['login']['hostname'] = '101.79.109.239';
	$db['login']['username'] = 'root';
	$db['login']['password'] = 'dudrud';
	//log single
	$db['log']['hostname'] = '101.79.109.239';
	$db['log']['username'] = 'root';
	$db['log']['password'] = 'dudrud';

	$db['default']['hostname'] = '101.79.109.239';
	$db['default']['username'] = 'root';
	$db['default']['password'] = 'dudrud';
	//replication slave
	$db['default_sel']['hostname'] = '101.79.109.239';
	$db['default_sel']['username'] = 'root';
	$db['default_sel']['password'] = 'dudrud';
	//replication master
	$db['default_ins']['hostname'] = '101.79.109.239';
	$db['default_ins']['username'] = 'root';
	$db['default_ins']['password'] = 'dudrud';

	$db['login']['dbdriver'] = 'mysqli';
	$db['login']['pconnect'] = TRUE;
	$db['log']['dbdriver'] = 'mysqli';
	$db['log']['pconnect'] = TRUE;
	$db['default']['dbdriver'] = 'mysqli';
	$db['default']['pconnect'] = FALSE;
	$db['default_sel']['dbdriver'] = 'mysqli';
	$db['default_sel']['pconnect'] = FALSE;
	$db['default_ins']['dbdriver'] = 'mysqli';
	$db['default_ins']['pconnect'] = FALSE;
}
else if ( ENVIRONMENT == 'staging' )
{
	// getsebool -a |grep httpd 외부에서 데이터베이스 접근하도록 설정되어있는 지 확인
	// setsebool httpd_can_network_connect_db=on 설정해야함.
	$db['default']['hostname'] = '54.64.86.88';
	$db['default']['username'] = 'root';
	$db['default']['password'] = 'dudrud';
	//replication slave
	$db['default_sel']['hostname'] = '54.64.86.88';
	$db['default_sel']['username'] = 'root';
	$db['default_sel']['password'] = 'dudrud';
	//replication master
	$db['default_ins']['hostname'] = '54.64.86.88';
	$db['default_ins']['username'] = 'root';
	$db['default_ins']['password'] = 'dudrud';
	$db['default']['dbdriver'] = 'mysql';
	$db['default']['pconnect'] = TRUE;
	$db['default_sel']['dbdriver'] = 'mysql';
	$db['default_sel']['pconnect'] = TRUE;
	$db['default_ins']['dbdriver'] = 'mysql';
	$db['default_ins']['pconnect'] = TRUE;
}
$db['login']['dbprefix'] = '';
$db['login']['db_debug'] = TRUE;
$db['login']['cache_on'] = FALSE;
$db['login']['cachedir'] = '';
$db['login']['char_set'] = 'utf8';
$db['login']['dbcollat'] = 'utf8_general_ci';
$db['log']['dbprefix'] = '';
$db['log']['db_debug'] = TRUE;
$db['log']['cache_on'] = FALSE;
$db['log']['cachedir'] = '';
$db['log']['char_set'] = 'utf8';
$db['log']['dbcollat'] = 'utf8_general_ci';
$db['default']['dbprefix'] = '';
$db['default']['db_debug'] = TRUE;
$db['default']['cache_on'] = FALSE;
$db['default']['cachedir'] = '';
$db['default']['char_set'] = 'utf8';
$db['default']['dbcollat'] = 'utf8_general_ci';
$db['default_sel']['dbprefix'] = '';
$db['default_sel']['db_debug'] = TRUE;
$db['default_sel']['cache_on'] = FALSE;
$db['default_sel']['cachedir'] = '';
$db['default_sel']['char_set'] = 'utf8';
$db['default_sel']['dbcollat'] = 'utf8_general_ci';
$db['default_ins']['dbprefix'] = '';
$db['default_ins']['db_debug'] = TRUE;
$db['default_ins']['cache_on'] = FALSE;
$db['default_ins']['cachedir'] = '';
$db['default_ins']['char_set'] = 'utf8';
$db['default_ins']['dbcollat'] = 'utf8_general_ci';

//20150224 loginserver add
$db['koc_play_login'] = $db['login'];
$db['koc_play_login']['database'] = 'koc_account';

//20150224 loginserver add
$db['koc_play_log'] = $db['log'];
$db['koc_play_log']['database'] = 'koc_play';

//20141210rep	$db['koc_mail'] = $db['default'];
$db['koc_mail_sel'] = $db['default_sel'];
$db['koc_mail_ins'] = $db['default_ins'];
//20141210rep	$db['koc_mail']['database'] = 'koc_mail';
$db['koc_mail_sel']['database'] = 'koc_mail';
$db['koc_mail_ins']['database'] = 'koc_mail';

//20141210rep	$db['koc_play'] = $db['default'];
$db['koc_play_sel'] = $db['default_sel'];
$db['koc_play_ins'] = $db['default_ins'];
//20141210rep	$db['koc_play']['database'] = 'koc_play';
$db['koc_play_sel']['database'] = 'koc_play';
$db['koc_play_ins']['database'] = 'koc_play';

//20141210rep	$db['koc_rank'] = $db['default'];
$db['koc_rank_sel'] = $db['default_sel'];
$db['koc_rank_ins'] = $db['default_ins'];
//20141210rep	$db['koc_rank']['database'] = 'koc_rank';
$db['koc_rank_sel']['database'] = 'koc_rank';
$db['koc_rank_ins']['database'] = 'koc_rank';

//20141210rep	$db['koc_record'] = $db['default'];
$db['koc_record_sel'] = $db['default_sel'];
$db['koc_record_ins'] = $db['default_ins'];
//20141210rep	$db['koc_record']['database'] = 'koc_record';
$db['koc_record_sel']['database'] = 'koc_record';
$db['koc_record_ins']['database'] = 'koc_record';

//20141210rep	$db['koc_ref'] = $db['default'];
$db['koc_ref_sel'] = $db['default_sel'];
$db['koc_ref_ins'] = $db['default_ins'];
//20141210rep	$db['koc_ref']['database'] = 'koc_ref';
$db['koc_ref_sel']['database'] = 'koc_ref';
$db['koc_ref_ins']['database'] = 'koc_ref';

//20141210rep	$db['koc_admin'] = $db['default'];
$db['koc_admin_sel'] = $db['default_sel'];
$db['koc_admin_ins'] = $db['default_ins'];
//20141210rep	$db['koc_admin']['database'] = 'koc_admin';
$db['koc_admin_sel']['database'] = 'koc_admin';
$db['koc_admin_ins']['database'] = 'koc_admin';
/* End of file database.php */
/* Location: ./application/config/database.php */
