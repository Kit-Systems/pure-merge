<?php
namespace Ipol\DPD\Currency;

class Converter implements \Ipol\DPD\Currency\ConverterInterface
{
    public function convert($amount, $currencyFrom, $currencyTo, $actualDate = false){
		#connect
		$db = new \DB(DB_DRIVER, DB_HOSTNAME, DB_USERNAME, DB_PASSWORD, DB_DATABASE, DB_PORT);
		
		$shipping_dpd_account_default = $db->query("SELECT value FROM " . DB_PREFIX . "setting WHERE `key` = 'shipping_dpd_account_default'");
		
		if($shipping_dpd_account_default->row['value'] == 'RU'){
			$currency = $db->query("SELECT value FROM " . DB_PREFIX . "setting WHERE `key` = 'shipping_dpd_russian_currency'");
		}elseif($shipping_dpd_account_default->row['value'] == 'KZ'){
			$currency = $db->query("SELECT value FROM " . DB_PREFIX . "setting WHERE `key` = 'shipping_dpd_kazahstan_currency'");
		}elseif($shipping_dpd_account_default->row['value'] == 'BY'){
			$currency = $db->query("SELECT value FROM " . DB_PREFIX . "setting WHERE `key` = 'shipping_dpd_belarus_currency'");
		}
		
		$query = $db->query("SELECT value FROM " . DB_PREFIX . "currency WHERE code = '" . $currency->row['value'] . "'");
		$value_course = $query->row['value'];
		
		if($currencyFrom !== $currencyTo){
			return $amount*$value_course;
		}else{
			return $amount;
		}
	}
}