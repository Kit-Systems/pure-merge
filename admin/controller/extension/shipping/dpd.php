<?php
# Разработчик: Ipol
# ipolh.com
# DPD - служба доставки

class ControllerExtensionShippingDpd extends Controller {
	private $error;
	
	public function index() {
			
		$this->load->language('extension/shipping/dpd');
		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('setting/setting');
		$this->load->model('extension/shipping/dpd');
		$this->load->model('localisation/weight_class');
		$config = $this->getOpencartConfig();
		
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->model_setting_setting->editSetting('shipping_dpd', $this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');
			
			$this->response->redirect($this->url->link('extension/shipping/dpd', 'user_token=' . $this->session->data['user_token'] . '&type=shipping', true));
		}
		
		# Heading_title
		$data['heading_title']		= $this->language->get('heading_title');
		
		# Text
		$data['text_status_info']	= $this->language->get('text_status_info');
		$data['text_edit']		= $this->language->get('text_edit');
		$data['text_main_setting']	= $this->language->get('text_main_setting');
		$data['text_main_setting_end']	= $this->language->get('text_main_setting_end');
		$data['text_dimensions']	= $this->language->get('text_dimensions');
		$data['text_dimensions_heading1']	= $this->language->get('text_dimensions_heading1');
		$data['text_dimensions_heading2']	= $this->language->get('text_dimensions_heading2');
		$data['text_dimensions_center']	= $this->language->get('text_dimensions_center');
		$data['text_dimensions_end1']	= $this->language->get('text_dimensions_end1');
		$data['text_dimensions_end2']	= $this->language->get('text_dimensions_end2');
		$data['text_dimensions_warning']	= $this->language->get('text_dimensions_warning');
		$data['text_auth_russian']	= $this->language->get('text_auth_russian');
		$data['text_auth_belarus']	= $this->language->get('text_auth_belarus');
		$data['text_auth_kazahstan']	= $this->language->get('text_auth_kazahstan');
		$data['text_h1_terminal']	= $this->language->get('text_h1_terminal');
		$data['text_h1_door']	= $this->language->get('text_h1_door');
		$data['text_enabled']	= $this->language->get('text_enabled');
		$data['text_disabled']	= $this->language->get('text_disabled');
		$data['text_percent']	= $this->language->get('text_percent');
		$data['text_fix']	= $this->language->get('text_fix');
		$data['text_non']	= $this->language->get('text_non');
		$data['text_service_procedures']	= $this->language->get('text_service_procedures');
		$data['text_step_1']	= $this->language->get('text_step_1');
		$data['text_step_2']	= $this->language->get('text_step_2');
		$data['text_step_3']	= $this->language->get('text_step_3');
		$data['text_step_4']	= $this->language->get('text_step_4');
		
		# Entry
		$data['entry_status_module']	= $this->language->get('entry_status_module');
		$data['entry_dpd_number']		= $this->language->get('entry_dpd_number');
		$data['entry_dpd_auth']		= $this->language->get('entry_dpd_auth');
		$data['entry_currency']		= $this->language->get('entry_currency');
		$data['entry_account_default']		= $this->language->get('entry_account_default');
		$data['entry_test']		= $this->language->get('entry_test');
		$data['entry_dpd_button']		= $this->language->get('entry_dpd_button');
		$data['entry_pvz']		= $this->language->get('entry_pvz');
		$data['entry_api_map']		= $this->language->get('entry_api_map');
		$data['entry_use_for']		= $this->language->get('entry_use_for');
		$data['entry_weight']		= $this->language->get('entry_weight');
		$data['entry_length']		= $this->language->get('entry_length');
		$data['entry_width']		= $this->language->get('entry_width');
		$data['entry_height']		= $this->language->get('entry_height');
		$data['entry_not_calculate']		= $this->language->get('entry_not_calculate');
		$data['entry_tariff_default']		= $this->language->get('entry_tariff_default');
		$data['entry_max_for_default']		= $this->language->get('entry_max_for_default');
		$data['entry_cart_equally_product']		= $this->language->get('entry_cart_equally_product');
		$data['entry_calculate_for_product']		= $this->language->get('entry_calculate_for_product');
		$data['entry_ceil']		= $this->language->get('entry_ceil');
		$data['entry_term_shipping']		= $this->language->get('entry_term_shipping');
		$data['entry_h_comission']		= $this->language->get('entry_h_comission');
		$data['entry_h_comission_info']		= $this->language->get('entry_h_comission_info');
		$data['entry_comission_for_collection']		= $this->language->get('entry_comission_for_collection');
		$data['entry_comission_for_product']		= $this->language->get('entry_comission_for_product');
		$data['entry_min_sum_comission']		= $this->language->get('entry_min_sum_comission');
		$data['entry_bind_payment']		= $this->language->get('entry_bind_payment');
		$data['entry_not_payment']		= $this->language->get('entry_not_payment');
		$data['entry_contact_face']		= $this->language->get('entry_contact_face');
		$data['entry_name_company']		= $this->language->get('entry_name_company');
		$data['entry_phone_sender']		= $this->language->get('entry_phone_sender');
		$data['entry_email_sender']		= $this->language->get('entry_email_sender');
		$data['entry_numb_r_sender']		= $this->language->get('entry_numb_r_sender');
		$data['entry_pass']		= $this->language->get('entry_pass');
		$data['entry_h_address']		= $this->language->get('entry_h_address');
		$data['entry_h_alert']		= $this->language->get('entry_h_alert');
		$data['entry_h_options']		= $this->language->get('entry_h_options');
		$data['entry_city_sender']		= $this->language->get('entry_city_sender');
		$data['entry_street_sender']	= $this->language->get('entry_street_sender');
		$data['entry_ab_street_sender']	= $this->language->get('entry_ab_street_sender');	
		$data['entry_house_sender']	= $this->language->get('entry_house_sender');
		$data['entry_corp_sender']	= $this->language->get('entry_corp_sender');
		$data['entry_structure_sender']	= $this->language->get('entry_structure_sender');
		$data['entry_poss_sender']	= $this->language->get('entry_poss_sender');
		$data['entry_office_sender']	= $this->language->get('entry_office_sender');
		$data['entry_apart_sender']	= $this->language->get('entry_apart_sender');
		$data['entry_terminal_sender']	= $this->language->get('entry_terminal_sender');
		$data['entry_departure_method']	= $this->language->get('entry_departure_method');
		$data['entry_payment_method_delivery']	= $this->language->get('entry_payment_method_delivery');
		$data['entry_dpd_transit_interval']	= $this->language->get('entry_dpd_transit_interval');
		$data['entry_delivery_time_interval']	= $this->language->get('entry_delivery_time_interval');
		$data['entry_quantity_places']	= $this->language->get('entry_quantity_places');
		$data['entry_content_sender']	= $this->language->get('entry_content_sender');
		$data['entry_desc_sender_desc']	= $this->language->get('entry_desc_sender_desc');
		$data['entry_val_cargo']	= $this->language->get('entry_val_cargo');
		$data['entry_weekend_delivery']	= $this->language->get('entry_weekend_delivery');
		$data['entry_condition']	= $this->language->get('entry_condition');
		$data['entry_loading_unloading']	= $this->language->get('entry_loading_unloading');
		$data['entry_return_doc']	= $this->language->get('entry_return_doc');
		$data['entry_wait_address']	= $this->language->get('entry_wait_address');
		$data['entry_order_mail']	= $this->language->get('entry_order_mail');
		$data['entry_name']	= $this->language->get('entry_name');
		$data['entry_status']	= $this->language->get('entry_status');
		$data['entry_sort_order']	= $this->language->get('entry_sort_order');
		$data['entry_description']	= $this->language->get('entry_description');
		$data['entry_image']	= $this->language->get('entry_image');
		$data['entry_markup']	= $this->language->get('entry_markup');
		$data['entry_markup_type']	= $this->language->get('entry_markup_type');
		$data['entry_set_accepted']	= $this->language->get('entry_set_accepted');
		$data['entry_mark_delivery_paid']	= $this->language->get('entry_mark_delivery_paid');
		$data['entry_track_status_dpd']	= $this->language->get('entry_track_status_dpd');
		$data['entry_import']	= $this->language->get('entry_import');
		
		$dpd_statuses = \Ipol\DPD\DB\Order\Model::StatusList($config);
		$data['dpd_statuses'] = array();
		
		foreach($dpd_statuses as $k => $row){
			$data['dpd_statuses'][] = array(
				'entry'	=> $row . ':',
				'key'	=> $k,
			);
		}
		
		# Help
		$data['help_number']		= $this->language->get('help_number');
		$data['help_auth']		= $this->language->get('help_auth');
		$data['help_account_default']		= $this->language->get('help_account_default');
		$data['help_test']		= $this->language->get('help_test');
		$data['help_api_map']		= $this->language->get('help_api_map');
		$data['help_tariff']		= $this->language->get('help_tariff');
		$data['help_cart_equally_product']		= $this->language->get('help_cart_equally_product');
		$data['help_calculate_for_product']		= $this->language->get('help_calculate_for_product');
		$data['help_ceil']		= $this->language->get('help_ceil');
		$data['help_not_payment']		= $this->language->get('help_not_payment');
		$data['help_contact_face']		= $this->language->get('help_contact_face');
		$data['help_name_company']		= $this->language->get('help_name_company');
		$data['help_city_sender']		= $this->language->get('help_city_sender');
		$data['help_ab_street_sender']	= $this->language->get('help_ab_street_sender');
		$data['help_departure_method']	= $this->language->get('help_departure_method');
		$data['help_val_cargo']	= $this->language->get('help_val_cargo');
		$data['help_weekend_delivery']	= $this->language->get('help_weekend_delivery');
		$data['help_loading_unloading']	= $this->language->get('help_loading_unloading');
		$data['help_return_doc']	= $this->language->get('help_return_doc');
		$data['help_order_mail']	= $this->language->get('help_order_mail');
		$data['help_mark_delivery_paid']	= $this->language->get('help_mark_delivery_paid');
		
		# Warning
		$data['warning_weekend_delivery']	= $this->language->get('warning_weekend_delivery');
		
		# Tabs
		$data['tab_main']			= $this->language->get('tab_main');
		$data['tab_general']		= $this->language->get('tab_general');
		$data['tab_dimensions']		= $this->language->get('tab_dimensions');
		$data['tab_calculate_shipping']		= $this->language->get('tab_calculate_shipping');
		$data['tab_sender']			= $this->language->get('tab_sender');
		$data['tab_recipient']		= $this->language->get('tab_recipient');
		$data['tab_desc_sender']	= $this->language->get('tab_desc_sender');
		$data['tab_status']			= $this->language->get('tab_status');
		$data['tab_russian']		= $this->language->get('tab_russian');
		$data['tab_belarus']		= $this->language->get('tab_belarus');
		$data['tab_kazahstan']		= $this->language->get('tab_kazahstan');
		$data['tab_door']			= $this->language->get('tab_door');
		$data['tab_terminal']		= $this->language->get('tab_terminal');
		$data['tab_to_door']		= $this->language->get('tab_to_door');
		$data['tab_from_terminal']	= $this->language->get('tab_from_terminal');
		$data['tab_service']		= $this->language->get('tab_service');

		# Buttons
		$data['button_save']		= $this->language->get('button_save');
		$data['button_cancel']		= $this->language->get('button_cancel');
		
		if (isset($this->session->data['success'])) {
			$data['success'] = $this->session->data['success'];
			unset($this->session->data['success']);
		} else {
			$data['success'] = '';
		}		
		
		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}
		
		if (isset($this->error['file_exists'])) {
			$data['error_file_exists'] = $this->error['file_exists'];
		} else {
			$data['error_file_exists'] = '';
		}
		
		if (isset($this->error['russian_number'])) {
			$data['error_russian_number'] = $this->error['russian_number'];
		} else {
			$data['error_russian_number'] = '';
		}
		
		if (isset($this->error['russian_auth'])) {
			$data['error_russian_auth'] = $this->error['russian_auth'];
		} else {
			$data['error_russian_auth'] = '';
		}
		
		if (isset($this->error['kazahstan_number'])) {
			$data['error_kazahstan_number'] = $this->error['kazahstan_number'];
		} else {
			$data['error_kazahstan_number'] = '';
		}
		
		if (isset($this->error['kazahstan_auth'])) {
			$data['error_kazahstan_auth'] = $this->error['kazahstan_auth'];
		} else {
			$data['error_kazahstan_auth'] = '';
		}
		
		if (isset($this->error['belarus_number'])) {
			$data['error_belarus_number'] = $this->error['belarus_number'];
		} else {
			$data['error_belarus_number'] = '';
		}
		
		if (isset($this->error['belarus_auth'])) {
			$data['error_belarus_auth'] = $this->error['belarus_auth'];
		} else {
			$data['error_belarus_auth'] = '';
		}
		
		if (isset($this->error['weight'])) {
			$data['error_weight'] = $this->error['weight'];
		} else {
			$data['error_weight'] = '';
		}
		
		if (isset($this->error['length'])) {
			$data['error_length'] = $this->error['length'];
		} else {
			$data['error_length'] = '';
		}
		
		if (isset($this->error['width'])) {
			$data['error_width'] = $this->error['width'];
		} else {
			$data['error_width'] = '';
		}
		
		if (isset($this->error['height'])) {
			$data['error_height'] = $this->error['height'];
		} else {
			$data['error_height'] = '';
		}
		
		if (isset($this->error['content'])) {
			$data['error_content'] = $this->error['content'];
		} else {
			$data['error_content'] = '';
		}
		
		if (isset($this->error['name_door'])) {
			$data['error_name_door'] = $this->error['name_door'];
		} else {
			$data['error_name_door'] = '';
		}
		
		if (isset($this->error['name_terminal'])) {
			$data['error_name_terminal'] = $this->error['name_terminal'];
		} else {
			$data['error_name_terminal'] = '';
		}

		$data['breadcrumbs'] = array();
		$data['breadcrumbs'][] = array('text' => $this->language->get('text_home'), 'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'], true));
		$data['breadcrumbs'][] = array('text' => $this->language->get('text_extension'), 'href' => $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=shipping', true));
		$data['breadcrumbs'][] = array('text' => $this->language->get('heading_title') . $this->language->get('text_version'), 'href' => $this->url->link('extension/shipping/dpd', 'user_token=' . $this->session->data['user_token'], true));

		$data['action']   = $this->url->link('extension/shipping/dpd', 'user_token=' . $this->session->data['user_token'], true);
		$data['cancel']   = $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=shipping', true);
		
		$data['user_token'] = $this->session->data['user_token'];
		
		# Currency
		$data['currencies'] = $this->model_extension_shipping_dpd->getCurrencies();
		
		# Array country
		$data['country_with_dpd'] = array();
		$data['country_with_dpd'][0]['value'] = 'RU';
		$data['country_with_dpd'][0]['name'] = 'Россия';
		$data['country_with_dpd'][1]['value'] = 'KZ';
		$data['country_with_dpd'][1]['name'] = 'Казахстан';
		$data['country_with_dpd'][2]['value'] = 'BY';
		$data['country_with_dpd'][2]['name'] = 'Беларусь';
		
		# Array dpd&buttons
		$data['buttons_with_dpd'] = array();
		$data['buttons_with_dpd'][0]['value'] = 0;
		$data['buttons_with_dpd'][0]['name'] = 'Только у заказов с доставкой DPD';
		$data['buttons_with_dpd'][1]['value'] = 1;
		$data['buttons_with_dpd'][1]['name'] = 'Всегда';
		
		# Array use for
		$data['use_for'] = array();
		$data['use_for'][0]['value'] = 0;
		$data['use_for'][0]['name'] = 'Для всего заказа';
		$data['use_for'][1]['value'] = 1;
		$data['use_for'][1]['name'] = 'Для товаров в заказе';
		
		# Array departure method
		$data['departure_method'] = array();
		$data['departure_method'][0]['value'] = 0;
		$data['departure_method'][0]['name'] = 'Мы хотим вызывать забор автоматически';
		$data['departure_method'][1]['value'] = 1;
		$data['departure_method'][1]['name'] = 'Мы будем возить заказы сами';
		
		# Array payment method delivery
		$data['payment_method_delivery'] = array();
		$data['payment_method_delivery'][0]['value'] = 0;
		$data['payment_method_delivery'][0]['name'] = 'У отправителя по безналичному расчёту';
		$data['payment_method_delivery'][1]['value'] = 'ОУП';
		$data['payment_method_delivery'][1]['name'] = 'Оплата у получателя наличными';
		$data['payment_method_delivery'][2]['value'] = 'ОУО';
		$data['payment_method_delivery'][2]['name'] = 'Оплата у отправителя наличными';
		
		# DPD Transit Time Interval
		$data['dpd_transit_interval'] = array();
		$data['dpd_transit_interval'][0]['value'] = '9-18';
		$data['dpd_transit_interval'][0]['name'] = 'в любое время с 09:00 до 18:00';
		$data['dpd_transit_interval'][1]['value'] = '9-13';
		$data['dpd_transit_interval'][1]['name'] = 'с 09:00 до 13:00';
		$data['dpd_transit_interval'][2]['value'] = '13-18';
		$data['dpd_transit_interval'][2]['name'] = 'с 13:00 до 18:00';
		
		# Delivery time interval
		$data['delivery_time_interval'] = array();
		$data['delivery_time_interval'][0]['value'] = '9-18';
		$data['delivery_time_interval'][0]['name'] = 'в любое время с 09:00 до 18:00';
		$data['delivery_time_interval'][1]['value'] = '9-14';
		$data['delivery_time_interval'][1]['name'] = 'с 09:00 до 14:00';
		$data['delivery_time_interval'][2]['value'] = '13-18';
		$data['delivery_time_interval'][2]['name'] = 'с 13:00 до 18:00';
		$data['delivery_time_interval'][3]['value'] = '18-22';
		$data['delivery_time_interval'][3]['name'] = 'с 18:00 да 22:00 (оплачивается дополнительно)';
		
		# Waiting on address
		$data['waiting_address'] = array();
		$data['waiting_address'][0]['value'] = 0;
		$data['waiting_address'][0]['name'] = '- Не установленно -';
		$data['waiting_address'][1]['value'] = 'ПРИМ';
		$data['waiting_address'][1]['name'] = 'С примеркой';
		$data['waiting_address'][2]['value'] = 'ПРОС';
		$data['waiting_address'][2]['name'] = 'Простая';
		$data['waiting_address'][3]['value'] = 'РАБТ';
		$data['waiting_address'][3]['name'] = 'С проверкой работоспособности';
		
		# Array use for
		$tariffs = $this->getTariffList();
		
		
		foreach($tariffs as $key => $tariff){
			$data['not_calculate_dpd'][] = array(
				'value'	=>	$key,
				'name'	=>	$tariff
			);
		}

		# Main settings
		if (isset($this->request->post['shipping_dpd_status'])) {
			$data['shipping_dpd_status'] = $this->request->post['shipping_dpd_status'];
		} else {
			$data['shipping_dpd_status'] = $this->config->get('shipping_dpd_status');
		}
		
		if (isset($this->request->post['shipping_dpd_russian_number'])) {
			$data['shipping_dpd_russian_number'] = $this->request->post['shipping_dpd_russian_number'];
		} else {
			$data['shipping_dpd_russian_number'] = $this->config->get('shipping_dpd_russian_number');
		}

		if (isset($this->request->post['shipping_dpd_russian_auth'])) {
			$data['shipping_dpd_russian_auth'] = $this->request->post['shipping_dpd_russian_auth'];
		} else {
			$data['shipping_dpd_russian_auth'] = $this->config->get('shipping_dpd_russian_auth');
		}
		
		if (isset($this->request->post['shipping_dpd_russian_currency'])) {
			$data['shipping_dpd_russian_currency'] = $this->request->post['shipping_dpd_russian_currency'];
		} else {
			$data['shipping_dpd_russian_currency'] = $this->config->get('shipping_dpd_russian_currency');
		}
		
		if (isset($this->request->post['shipping_dpd_kazahstan_number'])) {
			$data['shipping_dpd_kazahstan_number'] = $this->request->post['shipping_dpd_kazahstan_number'];
		} else {
			$data['shipping_dpd_kazahstan_number'] = $this->config->get('shipping_dpd_kazahstan_number');
		}

		if (isset($this->request->post['shipping_dpd_kazahstan_auth'])) {
			$data['shipping_dpd_kazahstan_auth'] = $this->request->post['shipping_dpd_kazahstan_auth'];
		} else {
			$data['shipping_dpd_kazahstan_auth'] = $this->config->get('shipping_dpd_kazahstan_auth');
		}
		
		if (isset($this->request->post['shipping_dpd_kazahstan_currency'])) {
			$data['shipping_dpd_kazahstan_currency'] = $this->request->post['shipping_dpd_kazahstan_currency'];
		} else {
			$data['shipping_dpd_kazahstan_currency'] = $this->config->get('shipping_dpd_kazahstan_currency');
		}
		
		if (isset($this->request->post['shipping_dpd_belarus_number'])) {
			$data['shipping_dpd_belarus_number'] = $this->request->post['shipping_dpd_belarus_number'];
		} else {
			$data['shipping_dpd_belarus_number'] = $this->config->get('shipping_dpd_belarus_number');
		}

		if (isset($this->request->post['shipping_dpd_belarus_auth'])) {
			$data['shipping_dpd_belarus_auth'] = $this->request->post['shipping_dpd_belarus_auth'];
		} else {
			$data['shipping_dpd_belarus_auth'] = $this->config->get('shipping_dpd_belarus_auth');
		}
		
		if (isset($this->request->post['shipping_dpd_belarus_currency'])) {
			$data['shipping_dpd_belarus_currency'] = $this->request->post['shipping_dpd_belarus_currency'];
		} else {
			$data['shipping_dpd_belarus_currency'] = $this->config->get('shipping_dpd_belarus_currency');
		}
		
		if (isset($this->request->post['shipping_dpd_account_default'])) {
			$data['shipping_dpd_account_default'] = $this->request->post['shipping_dpd_account_default'];
		} else {
			$data['shipping_dpd_account_default'] = $this->config->get('shipping_dpd_account_default');
		}
		
		if (isset($this->request->post['shipping_dpd_test'])) {
			$data['shipping_dpd_test'] = $this->request->post['shipping_dpd_test'];
		} else {
			$data['shipping_dpd_test'] = $this->config->get('shipping_dpd_test');
		}
		
		if (isset($this->request->post['shipping_dpd_button'])) {
			$data['shipping_dpd_button'] = $this->request->post['shipping_dpd_button'];
		} else {
			$data['shipping_dpd_button'] = $this->config->get('shipping_dpd_button');
		}
		
		if (isset($this->request->post['shipping_dpd_pvz'])) {
			$data['shipping_dpd_pvz'] = $this->request->post['shipping_dpd_pvz'];
		} else {
			$data['shipping_dpd_pvz'] = $this->config->get('shipping_dpd_pvz');
		}
		
		if (isset($this->request->post['shipping_dpd_api_map'])) {
			$data['shipping_dpd_api_map'] = $this->request->post['shipping_dpd_api_map'];
		} else {
			$data['shipping_dpd_api_map'] = $this->config->get('shipping_dpd_api_map');
		}
		
		if (isset($this->request->post['shipping_dpd_use_for'])) {
			$data['shipping_dpd_use_for'] = $this->request->post['shipping_dpd_use_for'];
		} else {
			$data['shipping_dpd_use_for'] = $this->config->get('shipping_dpd_use_for');
		}
		
		if (isset($this->request->post['shipping_dpd_weight'])) {
			$data['shipping_dpd_weight'] = $this->request->post['shipping_dpd_weight'];
		} elseif($this->config->get('shipping_dpd_weight')) {
			$data['shipping_dpd_weight'] = $this->config->get('shipping_dpd_weight');
		}else{
			$data['shipping_dpd_weight'] = 1000;
		}
		
		if (isset($this->request->post['shipping_dpd_length'])) {
			$data['shipping_dpd_length'] = $this->request->post['shipping_dpd_length'];
		} elseif($this->config->get('shipping_dpd_length')) {
			$data['shipping_dpd_length'] = $this->config->get('shipping_dpd_length');
		}else{
			$data['shipping_dpd_length'] = 200;
		}
		
		if (isset($this->request->post['shipping_dpd_width'])) {
			$data['shipping_dpd_width'] = $this->request->post['shipping_dpd_width'];
		} elseif($this->config->get('shipping_dpd_width')) {
			$data['shipping_dpd_width'] = $this->config->get('shipping_dpd_width');
		}else{
			$data['shipping_dpd_width'] = 100;
		}
		
		if (isset($this->request->post['shipping_dpd_height'])) {
			$data['shipping_dpd_height'] = $this->request->post['shipping_dpd_height'];
		} elseif($this->config->get('shipping_dpd_height')) {
			$data['shipping_dpd_height'] = $this->config->get('shipping_dpd_height');
		}else{
			$data['shipping_dpd_height'] = 200;
		}
		
		if (isset($this->request->post['shipping_dpd_not_calculate'])) {
			$data['shipping_dpd_not_calculate'] = $this->request->post['shipping_dpd_not_calculate'];
		} else {
			$data['shipping_dpd_not_calculate'] = $this->config->get('shipping_dpd_not_calculate');
		}
		
		if(is_array($data['shipping_dpd_not_calculate'])){
			foreach($data['shipping_dpd_not_calculate'] as $key => $mthd){
				$data['shipping_dpd_not_calculate' . $mthd] = $mthd;
			}
		}
		
		if (isset($this->request->post['shipping_dpd_tariff_default'])) {
			$data['shipping_dpd_tariff_default'] = $this->request->post['shipping_dpd_tariff_default'];
		} else {
			$data['shipping_dpd_tariff_default'] = $this->config->get('shipping_dpd_tariff_default');
		}
		
		if (isset($this->request->post['shipping_dpd_max_for_default'])) {
			$data['shipping_dpd_max_for_default'] = $this->request->post['shipping_dpd_max_for_default'];
		} else {
			$data['shipping_dpd_max_for_default'] = $this->config->get('shipping_dpd_max_for_default');
		}
		
		if (isset($this->request->post['shipping_dpd_cart_equally_product'])) {
			$data['shipping_dpd_cart_equally_product'] = $this->request->post['shipping_dpd_cart_equally_product'];
		} else {
			$data['shipping_dpd_cart_equally_product'] = $this->config->get('shipping_dpd_cart_equally_product');
		}
		
		if (isset($this->request->post['shipping_dpd_calculate_for_product'])) {
			$data['shipping_dpd_calculate_for_product'] = $this->request->post['shipping_dpd_calculate_for_product'];
		} else {
			$data['shipping_dpd_calculate_for_product'] = $this->config->get('shipping_dpd_calculate_for_product');
		}
		
		if (isset($this->request->post['shipping_dpd_ceil'])) {
			$data['shipping_dpd_ceil'] = $this->request->post['shipping_dpd_ceil'];
		} else {
			$data['shipping_dpd_ceil'] = $this->config->get('shipping_dpd_ceil');
		}
		
		if (isset($this->request->post['shipping_dpd_term_shipping'])) {
			$data['shipping_dpd_term_shipping'] = $this->request->post['shipping_dpd_term_shipping'];
		} else {
			$data['shipping_dpd_term_shipping'] = $this->config->get('shipping_dpd_term_shipping');
		}
		
		$this->load->model('customer/customer_group');
		
		$data['customer_groups'] = $this->model_customer_customer_group->getCustomerGroups();
		
		# Способы оплаты
		$results_payment = $this->model_extension_shipping_dpd->getExtensions('payment');
		
		foreach ($results_payment as $result) {
			if ($this->config->get('payment_' . $result['code'] . '_status')) {
				$title = $this->getPayment($result['code']);
				
				$method_data[] = array(
					'code'		 => $result['code'],
					'sort_order' => $this->config->get('payment_' . $result['code'] . '_sort_order'),
					'title'		 => $title
				);
			}
		}
		
		$sort_order = array();

		foreach ($method_data as $key => $value) {
			$sort_order[$key] = $value['sort_order'];
		}

		array_multisort($sort_order, SORT_ASC, $method_data);
		
		$data['payment_methods'] = $method_data;
		
		/* Настройки комиссии и минимальная группа по сортировке */
		foreach($data['customer_groups'] as $group){
			if (isset($this->request->post['shipping_dpd_comission_for_collection_' . $group['customer_group_id']])) {
				$data['shipping_dpd_comission_for_collection_'.$group['customer_group_id']] = $this->request->post['shipping_dpd_comission_for_collection_'.$group['customer_group_id']];
			} else {
				$data['shipping_dpd_comission_for_collection_'.$group['customer_group_id']] = $this->config->get('shipping_dpd_comission_for_collection_'.$group['customer_group_id']);
			}
			
			if (isset($this->request->post['shipping_dpd_comission_for_product_' . $group['customer_group_id']])) {
				$data['shipping_dpd_comission_for_product_'.$group['customer_group_id']] = $this->request->post['shipping_dpd_comission_for_product_'.$group['customer_group_id']];
			} else {
				$data['shipping_dpd_comission_for_product_'.$group['customer_group_id']] = $this->config->get('shipping_dpd_comission_for_product_'.$group['customer_group_id']);
			}
			
			if (isset($this->request->post['shipping_dpd_min_sum_comission_' . $group['customer_group_id']])) {
				$data['shipping_dpd_min_sum_comission_'.$group['customer_group_id']] = $this->request->post['shipping_dpd_min_sum_comission_'.$group['customer_group_id']];
			} else {
				$data['shipping_dpd_min_sum_comission_'.$group['customer_group_id']] = $this->config->get('shipping_dpd_min_sum_comission_'.$group['customer_group_id']);
			}
			
			if (isset($this->request->post['shipping_dpd_bind_payment_' . $group['customer_group_id']])) {
				$data['shipping_dpd_bind_payment_'.$group['customer_group_id']] = $this->request->post['shipping_dpd_bind_payment_'.$group['customer_group_id']];
			} else {
				$data['shipping_dpd_bind_payment_'.$group['customer_group_id']] = $this->config->get('shipping_dpd_bind_payment_'.$group['customer_group_id']);
			}
			
			if(is_array($data['shipping_dpd_bind_payment_'.$group['customer_group_id']])){
				foreach($data['shipping_dpd_bind_payment_'.$group['customer_group_id']] as $key => $mthd){
					$data['shipping_dpd_bind_payment_'.$group['customer_group_id'] . $mthd] = $mthd;
				}
			}
			
			if (isset($this->request->post['shipping_dpd_not_payment_' . $group['customer_group_id']])) {
				$data['shipping_dpd_not_payment_'.$group['customer_group_id']] = $this->request->post['shipping_dpd_not_payment_'.$group['customer_group_id']];
			} else {
				$data['shipping_dpd_not_payment_'.$group['customer_group_id']] = $this->config->get('shipping_dpd_not_payment_'.$group['customer_group_id']);
			}
			
			$minimum_customer_id[] = $group['customer_group_id'];
		}
		
		$data['minimum_customer_id'] = min($minimum_customer_id);
		
		/* Настройки комиссии и минимальная группа по сортировке */
		
		if (isset($this->request->post['shipping_dpd_contact_face'])) {
			$data['shipping_dpd_contact_face'] = $this->request->post['shipping_dpd_contact_face'];
		} else {
			$data['shipping_dpd_contact_face'] = $this->config->get('shipping_dpd_contact_face');
		}
		
		if (isset($this->request->post['shipping_dpd_name_company'])) {
			$data['shipping_dpd_name_company'] = $this->request->post['shipping_dpd_name_company'];
		} else {
			$data['shipping_dpd_name_company'] = $this->config->get('shipping_dpd_name_company');
		}
		
		if (isset($this->request->post['shipping_dpd_phone_sender'])) {
			$data['shipping_dpd_phone_sender'] = $this->request->post['shipping_dpd_phone_sender'];
		} else {
			$data['shipping_dpd_phone_sender'] = $this->config->get('shipping_dpd_phone_sender');
		}
		
		if (isset($this->request->post['shipping_dpd_email_sender'])) {
			$data['shipping_dpd_email_sender'] = $this->request->post['shipping_dpd_email_sender'];
		} else {
			$data['shipping_dpd_email_sender'] = $this->config->get('shipping_dpd_email_sender');
		}
		
		if (isset($this->request->post['shipping_dpd_numb_r_sender'])) {
			$data['shipping_dpd_numb_r_sender'] = $this->request->post['shipping_dpd_numb_r_sender'];
		} else {
			$data['shipping_dpd_numb_r_sender'] = $this->config->get('shipping_dpd_numb_r_sender');
		}
		
		if (isset($this->request->post['shipping_dpd_pass'])) {
			$data['shipping_dpd_pass'] = $this->request->post['shipping_dpd_pass'];
		} else {
			$data['shipping_dpd_pass'] = $this->config->get('shipping_dpd_pass');
		}
		
		if (isset($this->request->post['shipping_dpd_city_sender'])) {
			$data['shipping_dpd_city_sender'] = $this->request->post['shipping_dpd_city_sender'];
		} else {
			$data['shipping_dpd_city_sender'] = $this->config->get('shipping_dpd_city_sender');
		}
		
		if (isset($this->request->post['shipping_dpd_city_id'])) {
			$data['shipping_dpd_city_id'] = $this->request->post['shipping_dpd_city_id'];
		} else {
			$data['shipping_dpd_city_id'] = $this->config->get('shipping_dpd_city_id');
		}
		
		if (isset($this->request->post['shipping_dpd_street_sender'])) {
			$data['shipping_dpd_street_sender'] = $this->request->post['shipping_dpd_street_sender'];
		} else {
			$data['shipping_dpd_street_sender'] = $this->config->get('shipping_dpd_street_sender');
		}
		
		if (isset($this->request->post['shipping_dpd_ab_street_sender'])) {
			$data['shipping_dpd_ab_street_sender'] = $this->request->post['shipping_dpd_ab_street_sender'];
		} else {
			$data['shipping_dpd_ab_street_sender'] = $this->config->get('shipping_dpd_ab_street_sender');
		}
		
		if (isset($this->request->post['shipping_dpd_house_sender'])) {
			$data['shipping_dpd_house_sender'] = $this->request->post['shipping_dpd_house_sender'];
		} else {
			$data['shipping_dpd_house_sender'] = $this->config->get('shipping_dpd_house_sender');
		}
		
		if (isset($this->request->post['shipping_dpd_corp_sender'])) {
			$data['shipping_dpd_corp_sender'] = $this->request->post['shipping_dpd_corp_sender'];
		} else {
			$data['shipping_dpd_corp_sender'] = $this->config->get('shipping_dpd_corp_sender');
		}
		
		if (isset($this->request->post['shipping_dpd_structure_sender'])) {
			$data['shipping_dpd_structure_sender'] = $this->request->post['shipping_dpd_structure_sender'];
		} else {
			$data['shipping_dpd_structure_sender'] = $this->config->get('shipping_dpd_structure_sender');
		}
		
		if (isset($this->request->post['shipping_dpd_poss_sender'])) {
			$data['shipping_dpd_poss_sender'] = $this->request->post['shipping_dpd_poss_sender'];
		} else {
			$data['shipping_dpd_poss_sender'] = $this->config->get('shipping_dpd_poss_sender');
		}
		
		if (isset($this->request->post['shipping_dpd_office_sender'])) {
			$data['shipping_dpd_office_sender'] = $this->request->post['shipping_dpd_office_sender'];
		} else {
			$data['shipping_dpd_office_sender'] = $this->config->get('shipping_dpd_office_sender');
		}
		
		if (isset($this->request->post['shipping_dpd_apart_sender'])) {
			$data['shipping_dpd_apart_sender'] = $this->request->post['shipping_dpd_apart_sender'];
		} else {
			$data['shipping_dpd_apart_sender'] = $this->config->get('shipping_dpd_apart_sender');
		}
		
		if (isset($this->request->post['shipping_dpd_terminal_sender'])) {
			$data['shipping_dpd_terminal_sender'] = $this->request->post['shipping_dpd_terminal_sender'];
		} else {
			$data['shipping_dpd_terminal_sender'] = $this->config->get('shipping_dpd_terminal_sender');
		}
		
		if (isset($this->request->post['shipping_dpd_pass_rec'])) {
			$data['shipping_dpd_pass_rec'] = $this->request->post['shipping_dpd_pass_rec'];
		} else {
			$data['shipping_dpd_pass_rec'] = $this->config->get('shipping_dpd_pass_rec');
		}
		
		if (isset($this->request->post['shipping_dpd_departure_method'])) {
			$data['shipping_dpd_departure_method'] = $this->request->post['shipping_dpd_departure_method'];
		} else {
			$data['shipping_dpd_departure_method'] = $this->config->get('shipping_dpd_departure_method');
		}
		
		if (isset($this->request->post['shipping_dpd_payment_method_delivery'])) {
			$data['shipping_dpd_payment_method_delivery'] = $this->request->post['shipping_dpd_payment_method_delivery'];
		} else {
			$data['shipping_dpd_payment_method_delivery'] = $this->config->get('shipping_dpd_payment_method_delivery');
		}
		
		if (isset($this->request->post['shipping_dpd_transit_interval_dpd'])) {
			$data['shipping_dpd_transit_interval_dpd'] = $this->request->post['shipping_dpd_transit_interval_dpd'];
		} else {
			$data['shipping_dpd_transit_interval_dpd'] = $this->config->get('shipping_dpd_transit_interval_dpd');
		}
		
		if (isset($this->request->post['shipping_dpd_delivery_time_interval'])) {
			$data['shipping_dpd_delivery_time_interval'] = $this->request->post['shipping_dpd_delivery_time_interval'];
		} else {
			$data['shipping_dpd_delivery_time_interval'] = $this->config->get('shipping_dpd_delivery_time_interval');
		}
		
		if (isset($this->request->post['shipping_dpd_quantity_places'])) {
			$data['shipping_dpd_quantity_places'] = $this->request->post['shipping_dpd_quantity_places'];
		} else {
			$data['shipping_dpd_quantity_places'] = $this->config->get('shipping_dpd_quantity_places');
		}
		
		if (isset($this->request->post['shipping_dpd_content_sender'])) {
			$data['shipping_dpd_content_sender'] = $this->request->post['shipping_dpd_content_sender'];
		} else {
			$data['shipping_dpd_content_sender'] = $this->config->get('shipping_dpd_content_sender');
		}
		
		if (isset($this->request->post['shipping_dpd_val_cargo'])) {
			$data['shipping_dpd_val_cargo'] = $this->request->post['shipping_dpd_val_cargo'];
		} else {
			$data['shipping_dpd_val_cargo'] = $this->config->get('shipping_dpd_val_cargo');
		}
		
		if (isset($this->request->post['shipping_dpd_weekend_delivery'])) {
			$data['shipping_dpd_weekend_delivery'] = $this->request->post['shipping_dpd_weekend_delivery'];
		} else {
			$data['shipping_dpd_weekend_delivery'] = $this->config->get('shipping_dpd_weekend_delivery');
		}
		
		if (isset($this->request->post['shipping_dpd_condition'])) {
			$data['shipping_dpd_condition'] = $this->request->post['shipping_dpd_condition'];
		} else {
			$data['shipping_dpd_condition'] = $this->config->get('shipping_dpd_condition');
		}
		
		if (isset($this->request->post['shipping_dpd_loading_unloading'])) {
			$data['shipping_dpd_loading_unloading'] = $this->request->post['shipping_dpd_loading_unloading'];
		} else {
			$data['shipping_dpd_loading_unloading'] = $this->config->get('shipping_dpd_loading_unloading');
		}
		
		if (isset($this->request->post['shipping_dpd_return_doc'])) {
			$data['shipping_dpd_return_doc'] = $this->request->post['shipping_dpd_return_doc'];
		} else {
			$data['shipping_dpd_return_doc'] = $this->config->get('shipping_dpd_return_doc');
		}
		
		if (isset($this->request->post['shipping_dpd_wait_address'])) {
			$data['shipping_dpd_wait_address'] = $this->request->post['shipping_dpd_wait_address'];
		} else {
			$data['shipping_dpd_wait_address'] = $this->config->get('shipping_dpd_wait_address');
		}
		
		if (isset($this->request->post['shipping_dpd_order_mail'])) {
			$data['shipping_dpd_order_mail'] = $this->request->post['shipping_dpd_order_mail'];
		} else {
			$data['shipping_dpd_order_mail'] = $this->config->get('shipping_dpd_order_mail');
		}
		
		if (isset($this->request->post['shipping_dpd_name_door'])) {
			$data['shipping_dpd_name_door'] = $this->request->post['shipping_dpd_name_door'];
		} elseif($this->config->get('shipping_dpd_name_door')){
			$data['shipping_dpd_name_door'] = $this->config->get('shipping_dpd_name_door');
		}else{
			$data['shipping_dpd_name_door'] = 'До двери';
		}
		
		if (isset($this->request->post['shipping_dpd_door_status'])) {
			$data['shipping_dpd_door_status'] = $this->request->post['shipping_dpd_door_status'];
		} else {
			$data['shipping_dpd_door_status'] = $this->config->get('shipping_dpd_door_status');
		}
		
		if (isset($this->request->post['shipping_dpd_sort_order'])) {
			$data['shipping_dpd_sort_order'] = $this->request->post['shipping_dpd_sort_order'];
		} else {
			$data['shipping_dpd_sort_order'] = $this->config->get('shipping_dpd_sort_order');
		}
		
		if (isset($this->request->post['shipping_dpd_description_door'])) {
			$data['shipping_dpd_description_door'] = $this->request->post['shipping_dpd_description_door'];
		} else {
			$data['shipping_dpd_description_door'] = $this->config->get('shipping_dpd_description_door');
		}
		
		# Image
		if (isset($this->request->post['shipping_dpd_image_door'])) {
			$data['shipping_dpd_image_door'] = $this->request->post['shipping_dpd_image_door'];
		} else {
			$data['shipping_dpd_image_door'] = $this->config->get('shipping_dpd_image_door');
		}

		$this->load->model('tool/image');

		if (isset($this->request->post['shipping_dpd_image_door']) && is_file(DIR_IMAGE . $this->request->post['shipping_dpd_image_door'])) {
			$data['shipping_dpd_thumb_door'] = $this->model_tool_image->resize($this->request->post['shipping_dpd_image_door'], 100, 100);
		} elseif($this->config->get('shipping_dpd_image_door') && is_file(DIR_IMAGE . $this->config->get('shipping_dpd_image_door'))) {
			$data['shipping_dpd_thumb_door'] = $this->model_tool_image->resize($this->config->get('shipping_dpd_image_door'), 100, 100);
		}else{
			$data['shipping_dpd_thumb_door'] = $this->model_tool_image->resize('no_image.png', 100, 100);
		}
		
		$data['placeholder'] = $this->model_tool_image->resize('no_image.png', 100, 100);
		
		if (isset($this->request->post['shipping_dpd_markup_door'])) {
			$data['shipping_dpd_markup_door'] = $this->request->post['shipping_dpd_markup_door'];
		} else {
			$data['shipping_dpd_markup_door'] = $this->config->get('shipping_dpd_markup_door');
		}
		
		if (isset($this->request->post['shipping_dpd_markup_type_door'])) {
			$data['shipping_dpd_markup_type_door'] = $this->request->post['shipping_dpd_markup_type_door'];
		} else {
			$data['shipping_dpd_markup_type_door'] = $this->config->get('shipping_dpd_markup_type_door');
		}
		
		if (isset($this->request->post['shipping_dpd_name_terminal'])) {
			$data['shipping_dpd_name_terminal'] = $this->request->post['shipping_dpd_name_terminal'];
		} elseif($this->config->get('shipping_dpd_name_terminal')) {
			$data['shipping_dpd_name_terminal'] = $this->config->get('shipping_dpd_name_terminal');
		}else{
			$data['shipping_dpd_name_terminal'] = 'До терминала';
		}
		
		if (isset($this->request->post['shipping_dpd_terminal_status'])) {
			$data['shipping_dpd_terminal_status'] = $this->request->post['shipping_dpd_terminal_status'];
		} else {
			$data['shipping_dpd_terminal_status'] = $this->config->get('shipping_dpd_terminal_status');
		}
		
		if (isset($this->request->post['shipping_dpd_description_terminal'])) {
			$data['shipping_dpd_description_terminal'] = $this->request->post['shipping_dpd_description_terminal'];
		} else {
			$data['shipping_dpd_description_terminal'] = $this->config->get('shipping_dpd_description_terminal');
		}
		
		# Image
		if (isset($this->request->post['shipping_dpd_image_terminal'])) {
			$data['shipping_dpd_image_terminal'] = $this->request->post['shipping_dpd_image_terminal'];
		} else {
			$data['shipping_dpd_image_terminal'] = $this->config->get('shipping_dpd_image_terminal');
		}

		if (isset($this->request->post['shipping_dpd_image_terminal']) && is_file(DIR_IMAGE . $this->request->post['shipping_dpd_image_terminal'])) {
			$data['shipping_dpd_thumb_terminal'] = $this->model_tool_image->resize($this->request->post['shipping_dpd_image_terminal'], 100, 100);
		} elseif($this->config->get('shipping_dpd_image_terminal') && is_file(DIR_IMAGE . $this->config->get('shipping_dpd_image_terminal'))) {
			$data['shipping_dpd_thumb_terminal'] = $this->model_tool_image->resize($this->config->get('shipping_dpd_image_terminal'), 100, 100);
		}else{
			$data['shipping_dpd_thumb_terminal'] = $this->model_tool_image->resize('no_image.png', 100, 100);
		}
		
		if (isset($this->request->post['shipping_dpd_markup_terminal'])) {
			$data['shipping_dpd_markup_terminal'] = $this->request->post['shipping_dpd_markup_terminal'];
		} else {
			$data['shipping_dpd_markup_terminal'] = $this->config->get('shipping_dpd_markup_terminal');
		}
		
		if (isset($this->request->post['shipping_dpd_markup_type_terminal'])) {
			$data['shipping_dpd_markup_type_terminal'] = $this->request->post['shipping_dpd_markup_type_terminal'];
		} else {
			$data['shipping_dpd_markup_type_terminal'] = $this->config->get('shipping_dpd_markup_type_terminal');
		}
		
		if (isset($this->request->post['shipping_dpd_set_accepted'])) {
			$data['shipping_dpd_set_accepted'] = $this->request->post['shipping_dpd_set_accepted'];
		} else {
			$data['shipping_dpd_set_accepted'] = $this->config->get('shipping_dpd_set_accepted');
		}
		
		if (isset($this->request->post['shipping_dpd_mark_delivery_paid'])) {
			$data['shipping_dpd_mark_delivery_paid'] = $this->request->post['shipping_dpd_mark_delivery_paid'];
		} else {
			$data['shipping_dpd_mark_delivery_paid'] = $this->config->get('shipping_dpd_mark_delivery_paid');
		}
		
		if (isset($this->request->post['shipping_dpd_track_status_dpd'])) {
			$data['shipping_dpd_track_status_dpd'] = $this->request->post['shipping_dpd_track_status_dpd'];
		} else {
			$data['shipping_dpd_track_status_dpd'] = $this->config->get('shipping_dpd_track_status_dpd');
		}
		
		foreach($dpd_statuses as $k => $row){
			if (isset($this->request->post['shipping_dpd_status_' . $k])) {
				$data['shipping_dpd_status_' . $k] = $this->request->post['shipping_dpd_status_' . $k];
			} else {
				$data['shipping_dpd_status_' . $k] = $this->config->get('shipping_dpd_status_' . $k);
			}
		}
		
		if($this->config->get('shipping_dpd_city_id')){
			$data['terminals'] = $this->getTerminals($this->config->get('shipping_dpd_city_id'));
		}else{
			$data['terminals'] = array();
		}
		
		# Подключение таблицы
		$table   = \Ipol\DPD\DB\Connection::getInstance($config)->getTable('location');
				
		# Получение городов
		$items_city = $table->find(['select' => 'count(*) as cnt'])->fetch();
		
		# Проверка импорта
		if ($items_city['cnt'] <= 0) {
			$data['filled_city'] = 'Для работы модуля необходимо выполнить процедуру синхронизации!';
		}
		
		# Наличие подключения
		$data['filled'] = \Ipol\DPD\API\User\User::isActiveAccount($config);
		
		$this->load->model('localisation/order_status');
		
		$data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();
		
		$data['header'] 	 = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] 	 = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('extension/shipping/dpd', $data));
	}
	
	public function matchRegions(){
		$this->load->model('extension/shipping/dpd');
		$json = array();
		
		$regions = $this->model_extension_shipping_dpd->getRegions();
		
		if($regions){
			foreach($regions as $row){
				$code = $this->model_extension_shipping_dpd->getRegion($row['REGION_NAME']);
				$this->model_extension_shipping_dpd->updateRegions($row['REGION_NAME'], $code);
			}
			
			$json['success'] = 'Сопоставление успешно завершено!';
		}else{
			$json['error'] = 'Сначала воспроизведите импорт населенных пунктов!';
		}
		
		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
	
	# Import loadAll
	public function loadAll(){
		$json = array();
		
		ini_set('default_socket_timeout', 600);
		ini_set('max_execution_time', 600);
		
		try{
			$config  = $this->getOpencartConfig();
			$table   = \Ipol\DPD\DB\Connection::getInstance($config)->getTable('location');
			$api     = \Ipol\DPD\API\User\User::getInstanceByConfig($config);

			$loader = new \Ipol\DPD\DB\Location\Agent($api, $table);
			
			if(!isset($this->session->data['LoadAll'])){ 
				$this->session->data['LoadAll'][0] = 0; 
				$this->session->data['LoadAll'][1] = 9000000;
			}
			
			while($this->session->data['LoadAll'] !== true){
				$this->session->data['LoadAll'] = $loader->LoadAll($this->session->data['LoadAll'][0]);
				$this->response->redirect($this->url->link('extension/shipping/dpd/LoadAll', 'user_token=' . $this->session->data['user_token'] . '&type=shipping', true)); 
			}
			
			$json['success'] = 50000;
		} catch (\Exception $e) {
			$json['error'] = $e->getMessage();
		}
		
		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
	
	# Import loadCashPay
	public function loadCashPay(){
		$json = array();
		
		ini_set('default_socket_timeout', 600);
		ini_set('max_execution_time', 600);
		
		try{
			$config  = $this->getOpencartConfig();
			$table   = \Ipol\DPD\DB\Connection::getInstance($config)->getTable('location');
			$api     = \Ipol\DPD\API\User\User::getInstanceByConfig($config);

			$loader = new \Ipol\DPD\DB\Location\Agent($api, $table);
			
			if(!isset($this->session->data['loadCashPay'])){ 
				$this->session->data['loadCashPay'][0] = 0; 
				$this->session->data['loadCashPay'][1] = 9000000;
			}
			
			while($this->session->data['loadCashPay'] !== true){
				$this->session->data['loadCashPay'] = $loader->loadCashPay($this->session->data['loadCashPay'][0]);
				$this->response->redirect($this->url->link('extension/shipping/dpd/loadCashPay', 'user_token=' . $this->session->data['user_token'] . '&type=shipping', true)); 
			}
			
			$json['success'] = 50000;
		} catch (\Exception $e) {
			$json['error'] = $e->getMessage();
		}
		
		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
	
	# Import loadUnlimited
	public function loadUnlimited(){
		$json = array();
		
		ini_set('default_socket_timeout', 600);
		ini_set('max_execution_time', 600);
		
		$start = microtime(true);
		
		$config  = $this->getOpencartConfig();
		$table  = \Ipol\DPD\DB\Connection::getInstance($config)->getTable('terminal');
		$api    = \Ipol\DPD\API\User\User::getInstanceByConfig($config);

		$loader = new \Ipol\DPD\DB\Terminal\Agent($api, $table);
		$loader->loadUnlimited();
		
		$json['success'] = round(microtime(true) - $start, 0);
		$json['success'] = $json['success'].'00';
		
		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
	
	# Import loadLimited
	public function loadLimited(){
		$json = array();
		
		ini_set('default_socket_timeout', 600);
		ini_set('max_execution_time', 600);
		
		try{
			$config  = $this->getOpencartConfig();
			$table  = \Ipol\DPD\DB\Connection::getInstance($config)->getTable('terminal');
			$api    = \Ipol\DPD\API\User\User::getInstanceByConfig($config);

			$loader = new \Ipol\DPD\DB\Terminal\Agent($api, $table);
			
			if(!isset($this->session->data['loadLimited'])){ 
				$this->session->data['loadLimited'][0] = 0; 
				$this->session->data['loadLimited'][1] = 9000000;
			}
			
			while($this->session->data['loadLimited'] !== true){
				$this->session->data['loadLimited'] = $loader->loadLimited($this->session->data['loadLimited'][0]);
				$this->response->redirect($this->url->link('extension/shipping/dpd/loadLimited', 'user_token=' . $this->session->data['user_token'] . '&type=shipping', true)); 
			}
			
			$json['success'] = 50000;
		} catch (\Exception $e) {
			$json['error'] = $e->getMessage();
		}
			
		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
	
	# Unset
	public function unsetImport(){
		unset($this->session->data['LoadAll']);
		unset($this->session->data['loadCashPay']);
		unset($this->session->data['loadLimited']);
	}
	
	# Выгрузка сдк
	public function getLoadLocation(){
		ini_set('default_socket_timeout', 600);
		$config  = $this->getOpencartConfig();
		$table   = \Ipol\DPD\DB\Connection::getInstance($config)->getTable('location');
		$api     = \Ipol\DPD\API\User\User::getInstanceByConfig($config);

		$loader = new \Ipol\DPD\DB\Location\Agent($api, $table);
		$loader->loadAll();
		$loader->loadCashPay();
	}
	
	# Выгрузка сдк
	public function getLoadTerminal(){
		ini_set('default_socket_timeout', 600);
		$config  = $this->getOpencartConfig();
		$table  = \Ipol\DPD\DB\Connection::getInstance($config)->getTable('terminal');
		$api    = \Ipol\DPD\API\User\User::getInstanceByConfig($config);

		$loader = new \Ipol\DPD\DB\Terminal\Agent($api, $table);
		
		$loader->loadUnlimited();
		$loader->loadLimited();
	}
	
	# Список терминалов
	public function getTerminals($city_id){
		$config  = $this->getOpencartConfig();
		$terminalTable  = \Ipol\DPD\DB\Connection::getInstance($config)->getTable('terminal');
		
		$items = $terminalTable->find([
			'where' => 'LOCATION_ID = "' . $city_id . '" AND SCHEDULE_SELF_PICKUP != "" ORDER BY NAME',
		])->fetchAll();
		
		return $items;
	}
	
	# Список dpd доставок
	public function getTariffList(){
		
		$config  = $this->getOpencartConfig();
		$shipment = new \Ipol\DPD\Shipment($config);
		$tariffs = $shipment->calculator()->TariffList();
		
		return $tariffs;
	}
	
	# Получение списка оплат(вкл)
	public function getPayment($code){
		$this->load->language('extension/payment/' . $code);
		
		$title = $this->language->get('heading_title');
		
		return $title;
	}
	
	public function autocomplete() {
		$json = array();

		if (isset($this->request->get['filter_name'])) {
			
			$config = $this->getOpencartConfig();
			$table   = \Ipol\DPD\DB\Connection::getInstance($config)->getTable('location');
			
			$results = $table->find([
				'where' => 'CITY_NAME LIKE :name',
				'limit' => '0,5',
				'order'	=> 'IS_CITY DESC,COUNTRY_NAME DESC',
				'bind'  => [
					':name' => '%' . mb_strtolower($this->request->get['filter_name']) . '%'
				]
			])->fetchAll();

			foreach ($results as $result) {
				
				if($result['CITY_ABBR'] == 'Город'){
					$result['CITY_ABBR'] = 'г.';
				}else{
					$result['CITY_ABBR'] = $result['CITY_ABBR'] . '.';
				}
				
				$json[] = array(
					'city_id' => $result['CITY_ID'],
					'value'   => $result['CITY_NAME'],
					'name'    => strip_tags(html_entity_decode($result['ORIG_NAME'], ENT_QUOTES, 'UTF-8'))
				);
			}
		}
		
		$sort_order = array();

		foreach ($json as $key => $value) {
			$sort_order[$key] = $value['name'];
		}

		array_multisort($sort_order, SORT_ASC, $json);

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
	
	# Подключение
	public function getOpencartConfig (){
		require DIR_SYSTEM . 'dpd/src/autoload.php';
		
		$options = array(
			'KLIENT_NUMBER'   => $this->config->get('shipping_dpd_russian_number'),
			'KLIENT_KEY'      => $this->config->get('shipping_dpd_russian_auth'),
			'KLIENT_CURRENCY' => $this->config->get('shipping_dpd_russian_currency'),
			'API_DEF_COUNTRY' => $this->config->get('shipping_dpd_account_default') ? $this->config->get('shipping_dpd_account_default') : 'RU',
			'IS_TEST'         => $this->config->get('shipping_dpd_test') ? true : false,
			'KLIENT_NUMBER_KZ' => $this->config->get('shipping_dpd_kazahstan_number'),
			'KLIENT_KEY_KZ'   => $this->config->get('shipping_dpd_kazahstan_auth'),
			'KLIENT_CURRENCY_KZ' => $this->config->get('shipping_dpd_kazahstan_currency'),
			'KLIENT_NUMBER_BY' => $this->config->get('shipping_dpd_belarus_number'),
			'KLIENT_KEY_BY'   => $this->config->get('shipping_dpd_belarus_auth'),
			'KLIENT_CURRENCY_BY' => $this->config->get('shipping_dpd_belarus_currency'),
		);

		$options = array_merge($options, [
			'DB' => [
				'DSN'      => 'mysql:host=' . DB_HOSTNAME . ';dbname=' . DB_DATABASE,
				'USERNAME' => DB_USERNAME,
				'PASSWORD' => DB_PASSWORD,
				'DRIVER'   => null,
				'PDO'      => null,
			]
		]);

		$config  = new \Ipol\DPD\Config\Config($options);
		
		return $config;
	}
	
	# Проверка формы
	protected function validate() {
		if (!$this->user->hasPermission('modify', 'extension/shipping/dpd')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}
		
		if (!file_exists(DIR_SYSTEM . 'dpd/src/autoload.php')) {
				$this->error['file_exists'] = $this->language->get('error_file_exists');
		}
		
		if ($this->request->post['shipping_dpd_account_default'] == 'RU'){
		
			if ((utf8_strlen($this->request->post['shipping_dpd_russian_number']) < 1) || (utf8_strlen($this->request->post['shipping_dpd_russian_number']) > 40)) {
				$this->error['russian_number'] = $this->language->get('error_russian_number');
			}
			
			if ((utf8_strlen($this->request->post['shipping_dpd_russian_auth']) < 1) || (utf8_strlen($this->request->post['shipping_dpd_russian_auth']) > 40)) {
				$this->error['russian_auth'] = $this->language->get('error_russian_auth');
			}
		}elseif($this->request->post['shipping_dpd_account_default'] == 'KZ'){
			if ((utf8_strlen($this->request->post['shipping_dpd_kazahstan_number']) < 1) || (utf8_strlen($this->request->post['shipping_dpd_kazahstan_number']) > 40)) {
				$this->error['kazahstan_number'] = $this->language->get('error_russian_number');
			}
			
			if ((utf8_strlen($this->request->post['shipping_dpd_kazahstan_auth']) < 1) || (utf8_strlen($this->request->post['shipping_dpd_kazahstan_auth']) > 40)) {
				$this->error['kazahstan_auth'] = $this->language->get('error_russian_auth');
			}
		}elseif($this->request->post['shipping_dpd_account_default'] == 'BY'){
			if ((utf8_strlen($this->request->post['shipping_dpd_belarus_number']) < 1) || (utf8_strlen($this->request->post['shipping_dpd_belarus_number']) > 40)) {
				$this->error['belarus_number'] = $this->language->get('error_russian_number');
			}
			
			if ((utf8_strlen($this->request->post['shipping_dpd_belarus_auth']) < 1) || (utf8_strlen($this->request->post['shipping_dpd_belarus_auth']) > 40)) {
				$this->error['belarus_auth'] = $this->language->get('error_russian_auth');
			}
		}
		
		if ((utf8_strlen($this->request->post['shipping_dpd_weight']) < 1)) {
			$this->error['weight'] = $this->language->get('error_weight');
		}
		
		if ((utf8_strlen($this->request->post['shipping_dpd_length']) < 1)) {
			$this->error['length'] = $this->language->get('error_length');
		}
		
		if ((utf8_strlen($this->request->post['shipping_dpd_width']) < 1)) {
			$this->error['width'] = $this->language->get('error_width');
		}
		
		if ((utf8_strlen($this->request->post['shipping_dpd_height']) < 1)) {
			$this->error['height'] = $this->language->get('error_height');
		}
		
		if ((utf8_strlen($this->request->post['shipping_dpd_content_sender']) < 1)) {
			$this->error['content'] = $this->language->get('error_content');
		}
		
		if ((utf8_strlen($this->request->post['shipping_dpd_name_door']) < 1)) {
			$this->error['name_door'] = $this->language->get('error_name_door');
		}
		
		if ((utf8_strlen($this->request->post['shipping_dpd_name_terminal']) < 1)) {
			$this->error['name_terminal'] = $this->language->get('error_name_terminal');
		}
		
		if ($this->error && !isset($this->error['warning'])) {

			$this->error['warning'] = $this->language->get('error_warning');

		}
		
		return !$this->error;
	}
	
	public function install(){
		
		# Создать таблицу заказов
		$this->db->query("CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "dpd_order` (
		  `order_id` int(11) NOT NULL,
		  `dpd_id` varchar(255) NOT NULL,
		  `status_dpd` varchar(255) NOT NULL,
		  `payment_method_delivery` varchar(255) NOT NULL,
		  `shipping_method` varchar(255) NOT NULL,
		  `shipping_variable` varchar(255) NOT NULL,
		  `date_shipment` datetime NOT NULL,
		  `transit_interval_dpd` varchar(255) NOT NULL,
		  `delivery_time_interval` varchar(255) NOT NULL,
		  `weight` float NOT NULL,
		  `width` float NOT NULL,
		  `height` float NOT NULL,
		  `length` float NOT NULL,
		  `volume` float NOT NULL,
		  `quantity_places` int(11) NOT NULL,
		  `content_sender` varchar(255) NOT NULL,
		  `contact_face` varchar(255) NOT NULL,
		  `name_company` varchar(255) NOT NULL,
		  `phone_sender` varchar(255) NOT NULL,
		  `email_sender` varchar(255) NOT NULL,
		  `pass` int(11) NOT NULL,
		  `city_sender_id` varchar(255) NOT NULL,
		  `street_sender` varchar(255) NOT NULL,
		  `ab_street_sender` varchar(255) NOT NULL,
		  `house_sender` varchar(255) NOT NULL,
		  `corp_sender` varchar(255) NOT NULL,
		  `structure_sender` varchar(255) NOT NULL,
		  `poss_sender` varchar(255) NOT NULL,
		  `office_sender` varchar(255) NOT NULL,
		  `apart_sender` varchar(255) NOT NULL,
		  `terminal_sender` varchar(255) NOT NULL,
		  `contact_face_rec` varchar(255) NOT NULL,
		  `name_company_rec` varchar(255) NOT NULL,
		  `phone_rec` varchar(255) NOT NULL,
		  `email_rec` varchar(255) NOT NULL,
		  `pass_rec` int(11) NOT NULL,
		  `city_rec_id` varchar(255) NOT NULL,
		  `street_rec` varchar(255) NOT NULL,
		  `ab_street_rec` varchar(255) NOT NULL,
		  `house_rec` varchar(255) NOT NULL,
		  `corp_rec` varchar(255) NOT NULL,
		  `structure_rec` varchar(255) NOT NULL,
		  `poss_rec` varchar(255) NOT NULL,
		  `office_rec` varchar(255) NOT NULL,
		  `apart_rec` varchar(255) NOT NULL,
		  `terminal_rec` varchar(255) NOT NULL,
		  `comment_rec` varchar(255) NOT NULL,
		  `val_cargo` int(11) NOT NULL,
		  `weekend_delivery` int(11) NOT NULL,
		  `temperature` int(11) NOT NULL,
		  `loading_unloading` int(11) NOT NULL,
		  `return_doc` int(11) NOT NULL,
		  `wait_address` varchar(255) NOT NULL,
		  `order_mail` varchar(255) NOT NULL,
		  `declared_check` int(11) NOT NULL,
		  `declared_value` float NOT NULL,
		  `order_npp_check` int(11) NOT NULL,
		  `order_npp_value` float NOT NULL,
		  `products` text NOT NULL
		) ENGINE=MyISAM DEFAULT CHARSET=utf8 ;"); 
	}
	
	public function uninstall() {
		$this->db->query("DROP TABLE IF EXISTS `" . DB_PREFIX . "dpd_order`");
	}

}