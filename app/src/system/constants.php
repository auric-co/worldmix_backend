<?php 

	/*Security*/
	define('SECRETE_KEY', 'IajxETyHgKbWADSfG9pBmJuFkwlsZrtC');
	
	/* Database Connection */
	define('DB_USERNAME', 'worldmix_admin');
	define('DB_PASSWORD', 'password@2021');
	define('DB_HOST', 'localhost');
	define('DB_NAME', 'worldmix_app');
    /*easysendsms contants  */
    define('SMSUser','cnyachri2019');
    define('SMSPass', 'esm10153');
    define('SMSName', 'Worldmix');
	/*Data Type*/
	define('BOOLEAN', 	'1');
	define('INTEGER', 	'2');
	define('STRING', 	'3');

	/*Response Codes Codes*/
	define('SUCCESS_RESPONSE', 						200);
	define('CREATED', 								201);
	define('NOT_MODIFIED', 							304);
	define('BAD_REQUEST', 							400);
	define('UNAUTHORISED', 							401);
	define('FORBIDEN', 								403);
	define('NOT_FOUND',								404);
	define('UNPROCESSABLE_ENTITY', 					422);
	define('INTERNAL_SERVER_ERROR', 				500);