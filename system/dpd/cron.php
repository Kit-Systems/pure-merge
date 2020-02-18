<?php
error_reporting(E_ALL);

ini_set('display_errors', 1);
ini_set('default_socket_timeout', 600);
####
$_SERVER['SERVER_PORT'] = null;

#Rq
require_once(__DIR__ . '/../../config.php');
require_once(DIR_SYSTEM . 'startup.php');
require DIR_SYSTEM . 'dpd/src/autoload.php';

#connect
$db = new DB(DB_DRIVER, DB_HOSTNAME, DB_USERNAME, DB_PASSWORD, DB_DATABASE, DB_PORT);

#params
$shipping_dpd_russian_number = $db->query("SELECT value FROM " . DB_PREFIX . "setting WHERE `key` = 'shipping_dpd_russian_number'");
$shipping_dpd_russian_auth = $db->query("SELECT value FROM " . DB_PREFIX . "setting WHERE `key` = 'shipping_dpd_russian_auth'");
$shipping_dpd_russian_currency = $db->query("SELECT value FROM " . DB_PREFIX . "setting WHERE `key` = 'shipping_dpd_russian_currency'");
$shipping_dpd_test = $db->query("SELECT value FROM " . DB_PREFIX . "setting WHERE `key` = 'shipping_dpd_test'");
$shipping_dpd_status_stock = $db->query("SELECT value FROM " . DB_PREFIX . "setting WHERE `key` = 'shipping_dpd_status_stock'");
$shipping_dpd_status_ways = $db->query("SELECT value FROM " . DB_PREFIX . "setting WHERE `key` = 'shipping_dpd_status_ways'");
$shipping_dpd_status_point_issue = $db->query("SELECT value FROM " . DB_PREFIX . "setting WHERE `key` = 'shipping_dpd_status_point_issue'");
$shipping_dpd_status_ready_c_door = $db->query("SELECT value FROM " . DB_PREFIX . "setting WHERE `key` = 'shipping_dpd_status_ready_c_door'");
$shipping_dpd_status_over_c_door = $db->query("SELECT value FROM " . DB_PREFIX . "setting WHERE `key` = 'shipping_dpd_status_over_c_door'");
$shipping_dpd_status_issued_recipient = $db->query("SELECT value FROM " . DB_PREFIX . "setting WHERE `key` = 'shipping_dpd_status_issued_recipient'");
$shipping_dpd_status_problem = $db->query("SELECT value FROM " . DB_PREFIX . "setting WHERE `key` = 'shipping_dpd_status_problem'");
$shipping_dpd_status_refused = $db->query("SELECT value FROM " . DB_PREFIX . "setting WHERE `key` = 'shipping_dpd_status_refused'");
$shipping_dpd_track_status_dpd = $db->query("SELECT value FROM " . DB_PREFIX . "setting WHERE `key` = 'shipping_dpd_track_status_dpd'");


#opt
$options = array(
	'KLIENT_NUMBER'  	 => $shipping_dpd_russian_number->row['value'],
	'KLIENT_KEY'     	 => $shipping_dpd_russian_auth->row['value'],
	'KLIENT_CURRENCY' 	 => $shipping_dpd_russian_currency->row['value'],
	'IS_TEST'        	 => $shipping_dpd_test->row['value'] ? true : false,
	'STATUS_ORDER_CHECK' => 1,
);
# + cnf
$config  = new \Ipol\DPD\Config\Config($options);	

#cheked orders
try{
	$upload  = \Ipol\DPD\Agents::checkOrderStatus($config);
} catch (\Exception $e) {
	echo $e->getMessage();
}

#get orders
$table  = \Ipol\DPD\DB\Connection::getInstance($config)->getTable('order');
$orders = $table->find()->fetchAll();

#re status
foreach($orders as $order){
	$db->query("UPDATE " . DB_PREFIX . "dpd_order SET status_dpd = '" . $order['ORDER_STATUS'] . "', dpd_id = '" . $order['ORDER_NUM'] . "' WHERE order_id = '" . $order['ORDER_ID'] . "'");
	if(isset($shipping_dpd_track_status_dpd->row['value'])){
		if($order['ORDER_STATUS'] == 'OnTerminalPickup' && $shipping_dpd_status_stock->row['value'] !== 'non'){
			$db->query("UPDATE " . DB_PREFIX . "order SET order_status_id = '" . $shipping_dpd_status_stock->row['value'] . "' WHERE order_id = '" . $order['ORDER_ID'] . "'");
		}elseif($order['ORDER_STATUS'] == 'OnRoad' && $shipping_dpd_status_ways->row['value'] !== 'non'){
			$db->query("UPDATE " . DB_PREFIX . "order SET order_status_id = '" . $shipping_dpd_status_ways->row['value'] . "' WHERE order_id = '" . $order['ORDER_ID'] . "'");
		}elseif($order['ORDER_STATUS'] == 'OnTerminal' && $shipping_dpd_status_point_issue->row['value'] !== 'non'){
			$db->query("UPDATE " . DB_PREFIX . "order SET order_status_id = '" . $shipping_dpd_status_point_issue->row['value'] . "' WHERE order_id = '" . $order['ORDER_ID'] . "'");
		}elseif($order['ORDER_STATUS'] == 'OnTerminalDelivery' && $shipping_dpd_status_ready_c_door->row['value'] !== 'non'){
			$db->query("UPDATE " . DB_PREFIX . "order SET order_status_id = '" . $shipping_dpd_status_ready_c_door->row['value'] . "' WHERE order_id = '" . $order['ORDER_ID'] . "'");
		}elseif($order['ORDER_STATUS'] == 'Delivering' && $shipping_dpd_status_over_c_door->row['value'] !== 'non'){
			$db->query("UPDATE " . DB_PREFIX . "order SET order_status_id = '" . $shipping_dpd_status_over_c_door->row['value'] . "' WHERE order_id = '" . $order['ORDER_ID'] . "'");
		}elseif($order['ORDER_STATUS'] == 'Delivered' && $shipping_dpd_status_issued_recipient->row['value'] !== 'non'){
			$db->query("UPDATE " . DB_PREFIX . "order SET order_status_id = '" . $shipping_dpd_status_issued_recipient->row['value'] . "' WHERE order_id = '" . $order['ORDER_ID'] . "'");
		}elseif($order['ORDER_STATUS'] == 'Problem' && $shipping_dpd_status_problem->row['value'] !== 'non'){
			$db->query("UPDATE " . DB_PREFIX . "order SET order_status_id = '" . $shipping_dpd_status_problem->row['value'] . "' WHERE order_id = '" . $order['ORDER_ID'] . "'");
		}elseif($order['ORDER_STATUS'] == 'Canceled' && $shipping_dpd_status_refused->row['value'] !== 'non'){
			$db->query("UPDATE " . DB_PREFIX . "order SET order_status_id = '" . $shipping_dpd_status_refused->row['value'] . "' WHERE order_id = '" . $order['ORDER_ID'] . "'");
		}
	}
}

?>