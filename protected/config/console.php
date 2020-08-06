<?php
$libsPath = dirname(__FILE__).DIRECTORY_SEPARATOR.'../../../libs/';
// This is the configuration for yiic console application.
// Any writable CConsoleApplication properties can be configured here.
return array(
	'basePath'=>dirname(__FILE__).DIRECTORY_SEPARATOR.'..',
	'name'=>'My Console Application',

	// preloading 'log' component
	'preload'=>array('log'),
	// autoloading model and component classes
	'import'=>array(
		'application.models.*',
		'application.components.*',
	),

	// application components
	'components'=>array(

		// database settings are configured in database.php
		'db'=>require(dirname(__FILE__).'/database.php'),

		'log'=>array(
			'class'=>'CLogRouter',
			'routes'=>array(
				array(
					'class'=>'CFileLogRoute',
					'levels'=>'error, warning',
				),
			),
		),
		// MIE CLASSI
		'webRequest'=>require($libsPath.'/webRequest/webRequest.php'),
		'crypt'=>require($libsPath.'/crypt/crypt.php'),
		'Utils'=>require($libsPath.'/Utils/Utils.php'),
		'Settings'=>require($libsPath.'/NAPay/Settings.php'),

	),
	'params'=>array(
		'libsPath'=>$libsPath,
	),
);
