<?php
# Разработчик: Кузнецов Богдан	
# E-mail: bogdan199210@yandex.ru
# DPD - служба доставки

class ModelExtensionShippingDpd extends Model {
	public function getCurrencies() {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "currency ORDER BY title");

		return $query->rows;
	}
	
	public function getRegions() {
		$query = $this->db->query("SELECT REGION_NAME FROM b_ipol_dpd_location GROUP BY REGION_NAME");

		return $query->rows;
	}
	
	public function getRegion($name) {
		$query = $this->db->query("SELECT code FROM " . DB_PREFIX . "zone WHERE name LIKE '%" . $name . "%'");
		
		if($query->num_rows){
			return $query->row['code'];
		}else{
			$code = '';
			
			if($name == 'АО Ханты-Мансийский - Югра'){
				$code = 'KHM';
			}elseif($name == 'Брестская'){
				$code = 'BR';
			}elseif($name == 'Витебская'){
				$code = 'VI';
			}elseif($name == 'Гомельская'){
				$code = 'HO';
			}elseif($name == 'Кабардино-Балкарская'){
				$code = 'KB';
			}elseif($name == 'Карачаево-Черкесская'){
				$code = 'KC';
			}elseif($name == 'Саха /Якутия/'){
				$code = 'SA';
			}
			return $code;
		}		
	}
	
	public function updateRegions($region_name, $region_code){
		$this->db->query("UPDATE b_ipol_dpd_location SET REGION_CODE = '" . $region_code . "' WHERE REGION_NAME LIKE '%" . $region_name . "%'");
	}
	
	public function editDpdOrder($data, $order_id){
		$products = serialize($data['order_products']);
		
		if(isset($data['order_npp_value'])){
			$order_npp_value = $data['order_npp_value'];
		}else{
			$order_npp_value = 0;
		}
		
		if(isset($data['declared_value'])){
			$declared_value = $data['declared_value'];
		}else{
			$declared_value = 0;
		}
		
		if(isset($data['status'])){
			$data['status'] = $data['status'];
		}else{
			$data['status'] = 'NEW';
		}
		
		$date_shipment = date('Y-m-d', strtotime($data['date_shipment']));
		
		$this->db->query("UPDATE `" . DB_PREFIX . "dpd_order` SET status_dpd = '" . $data['status'] . "', 
			payment_method_delivery = '" . $data['payment_method_delivery'] . "', shipping_method = '" . $data['shipping_method'] . "',
			shipping_variable = '" . $data['variable_delivery'] . "', transit_interval_dpd = '" . $data['transit_interval'] . "',
			delivery_time_interval = '" . $data['time_interval'] . "', weight = '" . $data['weight'] . "', width = '" . $data['width'] . "',
			height = '" . $data['height'] . "',  length = '" . $data['length'] . "', volume = '" . $data['volume'] . "',
			quantity_places = '" . $data['quantity_places'] . "', content_sender = '" . $data['content_sender'] . "',
			contact_face = '" . $data['contact_face'] . "', name_company = '" . $data['name_company'] . "',
			phone_sender = '" . $data['phone_sender'] . "', email_sender = '" . $data['email_sender'] . "',
			pass = '" . $data['pass'] . "', street_sender = '" . $data['street_sender'] . "',
			ab_street_sender = '" . $data['ab_street_sender'] . "', house_sender = '" . $data['house_sender'] . "',
			corp_sender = '" . $data['corp_sender'] . "', structure_sender = '" . $data['structure_sender'] . "',
			poss_sender = '" . $data['poss_sender'] . "', office_sender = '" . $data['office_sender'] . "',
			apart_sender = '" . $data['apart_sender'] . "', terminal_sender = '" . $data['terminal_sender'] . "',
			contact_face_rec = '" . $data['contact_face_rec'] . "', name_company_rec = '" . $data['name_company_rec'] . "',
			phone_rec = '" . $data['phone_rec'] . "', email_rec = '" . $data['email_rec'] . "',
			pass_rec = '" . $data['pass_rec'] . "', terminal_rec = '" . $data['terminal_rec'] . "',
			street_rec = '" . $data['street_rec'] . "', comment_rec = '" . $data['comment_rec'] . "',
			ab_street_rec = '" . $data['ab_street_rec'] . "', house_rec = '" . $data['house_rec'] . "',
			corp_rec = '" . $data['corp_rec'] . "', structure_rec = '" . $data['structure_rec'] . "',
			poss_rec = '" . $data['poss_rec'] . "', office_rec = '" . $data['office_rec'] . "',
			apart_rec = '" . $data['apart_rec'] . "', val_cargo = '" . $data['val_cargo'] . "',
			weekend_delivery = '" . $data['weekend_delivery'] . "', temperature = '" . $data['condition'] . "',
			loading_unloading = '" . $data['loading_unloading'] . "', return_doc = '" . $data['return_doc'] . "',
			wait_address = '" . $data['wait_address'] . "', order_mail = '" . $data['order_mail'] . "',
			order_npp_check = '" . $data['order_npp_check'] . "', order_npp_value = '" . $order_npp_value . "',
			declared_check = '" . $data['declared_check'] . "', declared_value = '" . $declared_value . "',
			date_shipment = '" . $date_shipment . "', products = '" . $products . "' WHERE order_id = '" . $order_id . "'");
	}
	
	public function addDpdOrder($data, $order_id){
		$products = serialize($data['order_products']);
		
		if(isset($data['order_npp_value'])){
			$order_npp_value = $data['order_npp_value'];
		}else{
			$order_npp_value = 0;
		}
		
		if(isset($data['declared_value'])){
			$declared_value = $data['declared_value'];
		}else{
			$declared_value = 0;
		}
		
		if(isset($data['status'])){
			$data['status'] = $data['status'];
		}else{
			$data['status'] = 'NEW';
		}
		
		$date_shipment = date('Y-m-d', strtotime($data['date_shipment']));
		
		$this->db->query("UPDATE `" . DB_PREFIX . "dpd_order` SET order_id = '" . $order_id . "', status_dpd = '" . $data['status'] . "',
			payment_method_delivery = '" . $data['payment_method_delivery'] . "', shipping_method = '" . $data['shipping_method'] . "',
			shipping_variable = '" . $data['variable_delivery'] . "', transit_interval_dpd = '" . $data['transit_interval'] . "',
			delivery_time_interval = '" . $data['time_interval'] . "', weight = '" . $data['weight'] . "', width = '" . $data['width'] . "',
			height = '" . $data['height'] . "',  length = '" . $data['length'] . "', volume = '" . $data['volume'] . "',
			quantity_places = '" . $data['quantity_places'] . "', content_sender = '" . $data['content_sender'] . "',
			contact_face = '" . $data['contact_face'] . "', name_company = '" . $data['name_company'] . "',
			phone_sender = '" . $data['phone_sender'] . "', email_sender = '" . $data['email_sender'] . "',
			pass = '" . $data['pass'] . "', street_sender = '" . $data['street_sender'] . "',
			ab_street_sender = '" . $data['ab_street_sender'] . "', house_sender = '" . $data['house_sender'] . "',
			corp_sender = '" . $data['corp_sender'] . "', structure_sender = '" . $data['structure_sender'] . "',
			poss_sender = '" . $data['poss_sender'] . "', office_sender = '" . $data['office_sender'] . "',
			apart_sender = '" . $data['apart_sender'] . "', terminal_sender = '" . $data['terminal_sender'] . "',
			contact_face_rec = '" . $data['contact_face_rec'] . "', name_company_rec = '" . $data['name_company_rec'] . "',
			phone_rec = '" . $data['phone_rec'] . "', email_rec = '" . $data['email_rec'] . "',
			pass_rec = '" . $data['pass_rec'] . "', terminal_rec = '" . $data['terminal_rec'] . "',
			street_rec = '" . $data['street_rec'] . "', comment_rec = '" . $data['comment_rec'] . "',
			ab_street_rec = '" . $data['ab_street_rec'] . "', house_rec = '" . $data['house_rec'] . "',
			corp_rec = '" . $data['corp_rec'] . "', structure_rec = '" . $data['structure_rec'] . "',
			poss_rec = '" . $data['poss_rec'] . "', office_rec = '" . $data['office_rec'] . "',
			apart_rec = '" . $data['apart_rec'] . "', val_cargo = '" . $data['val_cargo'] . "',
			weekend_delivery = '" . $data['weekend_delivery'] . "', temperature = '" . $data['condition'] . "',
			loading_unloading = '" . $data['loading_unloading'] . "', return_doc = '" . $data['return_doc'] . "',
			wait_address = '" . $data['wait_address'] . "', order_mail = '" . $data['order_mail'] . "',
			order_npp_check = '" . $data['order_npp_check'] . "', order_npp_value = '" . $order_npp_value . "',
			declared_check = '" . $data['declared_check'] . "', declared_value = '" . $declared_value . "',
			date_shipment = '" . $date_shipment . "', products = '" . $products . "'");
	}
	
	public function getExtensions($type) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "extension WHERE `type` = '" . $this->db->escape($type) . "'");

		return $query->rows;
	}
	
	public function getOrderDpd($order_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "dpd_order WHERE order_id = '" . (int)$order_id . "'");

		return $query->row;
	}
	
	public function getOrderDpdID($order_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "dpd_order WHERE order_id = '" . (int)$order_id . "' AND dpd_id != ''");

		return $query->row;
	}
	
	public function getCityes($data = array()) {
		$sql = "SELECT * FROM " . DB_PREFIX . "city_dpd WHERE city_id != '0'";

		if (!empty($data['filter_name'])) {
			$sql .= " AND city_name LIKE '" . $this->db->escape($data['filter_name']) . "%'";
		}

		$sql .= " ORDER BY city_name ASC";
		
		if (isset($data['start']) || isset($data['limit'])) {

			if ($data['start'] < 0) {
					$data['start'] = 0;
			}

			if ($data['limit'] < 1) {
				$data['limit'] = 20;
			}

			$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
		}

		$query = $this->db->query($sql);

		return $query->rows;
	}
}