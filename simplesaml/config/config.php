<?php
/* 
 * The configuration of simpleSAMLphp
 * 
 * $Id: config.php 3246 2013-05-23 11:43:52Z olavmrk $
 */

$config = array (
	'baseurlpath'           => 'simplesaml/',
	'certdir'               => 'cert/',
	'loggingdir'            => 'log/',
	'datadir'               => 'data/',
	'tempdir'               => '/tmp/simplesaml',

	'debug' => FALSE,
	'showerrors'            =>	TRUE,
	'debug.validatexml' => FALSE,

	'auth.adminpassword'		=> 'AppsSecretPassword',
	'admin.protectindexpage'	=> false,
	'admin.protectmetadata'	=> false,


	'secretsalt' => 's0JsJkchschsshh00shhakhsa0ahckak',

	'technicalcontact_name'     => 'Administrator',
	'technicalcontact_email'    => 'hostmaster@vla.vic.gov.au',

	'timezone' => 'Australia/Melbourne',
	'logging.level'         => SimpleSAML_Logger::NOTICE,
	'logging.handler'       => 'syslog',
	'logging.facility' => defined('LOG_LOCAL5') ? constant('LOG_LOCAL5') : LOG_USER,
	'logging.processname' => 'simplesamlphp',
	'logging.logfile'		=> 'simplesamlphp.log',



/* Which functionality in simpleSAMLphp do you want to enable */
	'enable.saml20-idp'		=> false,
	'enable.shib13-idp'		=> false,
	'enable.adfs-idp'		=> false,
	'enable.wsfed-sp'		=> false,
	'enable.authmemcookie' => false,


	'session.duration'		=>  8 * (60*60), // 8 hours.
	'session.datastore.timeout' => (4*60*60), // 4 hours
	'session.state.timeout' => (60*60), // 1 hour

/* Cookie domain. */
	'session.cookie.name' => 'SimpleSAMLSessionID',
	'session.cookie.lifetime' => 0,
	'session.cookie.path' => '/',
//	'session.cookie.domain' => NULL,
	'session.cookie.domain' => '.vla.vic.gov.au',
	'session.cookie.secure' => FALSE,
	'session.disable_fallback' => FALSE,
	'session.phpsession.cookiename'  => null,
	'session.phpsession.savepath'    => null,
	'session.phpsession.httponly'    => FALSE,

	'session.authtoken.cookiename' => 'SimpleSAMLAuthToken',
	

	'authproc.sp' => array(
		10 => array(
			'class' => 'core:AttributeMap', 'adfsclaim2name'
		),
	),
	

	'metadata.sources' => array(
		array('type' => 'flatfile'),
	),


/* Configure the datastore for simpleSAMLphp. */
	'store.type' => 'sql',
	'store.sql.dsn' => 'mysql:host=localhost;dbname=db_saml',
	'store.sql.username' => 'dbu_saml',
	'store.sql.password' => 'dbu_saml',
	'store.sql.prefix' => 'simpleSAMLphp',
);
