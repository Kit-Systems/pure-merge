<?php
require_once(str_replace('/system/library/vendor/ocext/ocext_smart_exchange.php', '', $_SERVER['SCRIPT_FILENAME']).'/config.php');
ini_set('log_errors', 'On');
ini_set('error_log', DIR_SYSTEM.'library/vendor/ocext/cache/php_errors.log');
date_default_timezone_set('UTC');
$exchange_link_token = '';
require_once(DIR_SYSTEM . 'library/vendor/ocext/anydsvxls_setting_version.php');
$exchange = new anyDSVXLSSettingVersion(array(),  '', '','');
$exchange->smartExchangeTask($exchange_link_token);


